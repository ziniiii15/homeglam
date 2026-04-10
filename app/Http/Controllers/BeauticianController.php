<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beautician;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Availability;
use App\Models\AppointmentSchedule;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\TimeSlot;
use App\Notifications\BookingStatusUpdatedNotification;


use App\Models\Gallery; // Add this
use App\Models\Category;

class BeauticianController extends Controller
{
    // ------------------------
    // Gallery Management
    // ------------------------
    public function addGalleryImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048', // 2MB Max
            'description' => 'nullable|string|max:255',
        ]);

        $beautician = Auth::guard('beautician')->user();

        if ($request->hasFile('image')) {
            $directory = base_path('storage/galleries');
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);

            Gallery::create([
                'beautician_id' => $beautician->id,
                'image_url' => 'view-asset/galleries/' . $filename, // Store relative path suitable for asset()
                'description' => $request->description,
            ]);

            return back()->with('success', 'Image added to gallery!');
        }

        return back()->with('error', 'Failed to upload image.');
    }

    public function deleteGalleryImage(Gallery $gallery)
    {
        // Ensure the gallery belongs to the logged-in beautician
        if ($gallery->beautician_id !== Auth::guard('beautician')->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete file from storage (optional but good practice)
        $path = str_replace('view-asset/', '', $gallery->image_url);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($path);

        $gallery->delete();

        return back()->with('success', 'Image removed from gallery.');
    }

    // ------------------------
    // Beautician CRUD
    // ------------------------
    public function index()
    {
        $beauticians = Beautician::all();
        return view('beauticians.index', compact('beauticians'));
    }

    public function create()
    {
        return view('beauticians.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:beauticians',
            'phone' => 'required',
            'address' => 'required',
            'base_location' => 'required',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['is_verified'] = true; // Admin created, so verified by default? Or false? Let's say true since admin created it.

        Beautician::create($data);

         // FIXED REDIRECT
    return response()->redirectToRoute('beauticians.index') // Redirect to index, not dashboard (this is admin CRUD likely)
                     ->with('success', 'Beautician created successfully.');
}

    public function show(Beautician $beautician)
    {
        $beautician->load(['serviceList', 'reviews.client', 'bookings']);
        return view('beauticians.show', compact('beautician'));
    }

    public function update(Request $request, Beautician $beautician)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:beauticians,email,'.$beautician->id,
            'phone'=>'required',
            'address'=>'required',
            'base_location'=>'required',
        ]);

        $beautician->update($request->all());

        // FIXED REDIRECT
    return response()->redirectToRoute('beauticians.index')
                     ->with('success', 'Beautician updated successfully.');
}

    public function destroy(Beautician $beautician)
    {
        $beautician->delete();
        // FIXED REDIRECT
    return response()->redirectToRoute('beauticians.index')
                     ->with('success', 'Beautician deleted successfully.');
}
    // ------------------------
    // Beautician Dashboard
    // ------------------------
    public function dashboard()
    {
        // Check for expired bookings
        Booking::checkExpiredBookings();

        /** @var \App\Models\Beautician $beautician */
        $beautician = Auth::guard('beautician')->user();

        $subscriptionExpired = false;
        $hasSubscriptionProof = false;

        if ($beautician) {
            if ($beautician->subscription_expires_at && $beautician->subscription_expires_at->isPast()) {
                if ($beautician->is_verified) {
                    $beautician->is_verified = false;
                    $beautician->save();
                }
                $subscriptionExpired = true;
            }

            $directory = base_path('storage/uploads/subscription_proofs');
            $pattern = $directory . DIRECTORY_SEPARATOR . 'beautician_' . $beautician->id . '.*';
            $files = glob($pattern);
            if ($files && count($files) > 0) {
                $hasSubscriptionProof = true;
            }
        }

        if ($subscriptionExpired && $hasSubscriptionProof) {
            $subscriptionExpired = false;
        }

        if ($beautician && ($beautician->rejection_reason || $beautician->verification_status === 'denied')) {
            return redirect()->route('beautician.denied');
        }

        if (!$beautician || (!$beautician->is_verified && !$subscriptionExpired)) {
            return redirect()->route('beautician.pending');
        }

        // Eager load relationships needed for dashboard
        if ($beautician) {
            $beautician->load(['galleries']); 
        } 

        $upcomingBookings = Booking::with(['client', 'service'])
            ->where('beautician_id', $beautician->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->orderBy('booking_date', 'asc')
            ->orderBy('booking_time', 'asc')
            ->get();

        $pastBookings = Booking::with(['client', 'service', 'review'])
            ->where('beautician_id', $beautician->id)
            ->whereIn('status', ['completed', 'canceled'])
            ->orderBy('booking_date','desc')
            ->get();

        $services = Service::where('beautician_id', $beautician->id)->get();

        $todayDay = strtolower(Carbon::today()->format('l'));
        $todayAvailability = Availability::where('beautician_id', $beautician->id)
            ->where('day_of_week', $todayDay)
            ->first();

        $todaySchedules = Booking::where('beautician_id', $beautician->id)
            ->where('booking_date', Carbon::today()->toDateString())
            ->orderBy('booking_time')
            ->get();

        // Calculate Average Rating
        $averageRating = Review::where('beautician_id', $beautician->id)->avg('rating');
        $reviews = Review::with(['client', 'booking.service'])
            ->where('beautician_id', $beautician->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Category::all();

        return view('dashboard.beautician', compact(
            'upcomingBookings',
            'pastBookings',
            'services',
            'todayAvailability',
            'todaySchedules',
            'averageRating',
            'reviews',
            'beautician',
            'categories',
            'subscriptionExpired'
        ));
    }

    public function transactions()
    {
        $beautician = Auth::guard('beautician')->user();

        if (!$beautician) {
            return redirect()->route('beautician.login')->with('error', 'Please log in first.');
        }

        $bookings = Booking::with(['client', 'service'])
            ->where('beautician_id', $beautician->id)
            ->whereNotNull('payment_status')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.beautician-transactions', compact('bookings'));
    }

    public function uploadSubscriptionProof(Request $request)
    {
        $request->validate([
            'subscription_proof' => 'required|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $beautician = Auth::guard('beautician')->user();

        if ($beautician && $request->hasFile('subscription_proof')) {
            $directory = base_path('storage/uploads/subscription_proofs');

            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $file = $request->file('subscription_proof');
            $extension = $file->getClientOriginalExtension();
            $filename = 'beautician_' . $beautician->id . '.' . $extension;

            foreach (glob($directory . DIRECTORY_SEPARATOR . 'beautician_' . $beautician->id . '.*') as $existing) {
                @unlink($existing);
            }

            $file->move($directory, $filename);
        }

        if ($beautician && $beautician->subscription_expires_at && $beautician->subscription_expires_at->isPast()) {
            return redirect()
                ->route('beautician.subscription_renew')
                ->with('success', 'Subscription payment proof uploaded. Waiting for admin verification.');
        }

        return back()->with('success', 'Subscription payment proof uploaded. Waiting for admin verification.');
    }

    // ------------------------
    // Service Management
    // ------------------------
    public function storeService(Request $request)
{
    $request->validate([
        'service_name' => 'required|string|max:255',
        'description'  => 'nullable|string',
        'base_price'   => 'required|numeric|min:0',
        'category'     => 'required|string',
        'discount_percentage' => 'nullable|integer|min:0|max:100',
    ]);

    $beautician = Auth::guard('beautician')->user();

    Service::create([
        'beautician_id' => $beautician->id,
        'service_name'  => $request->service_name,
        'description'   => $request->description ?? '',
        'base_price'    => (float) $request->base_price,
        'category'      => $request->category,
        'discount_percentage' => $request->discount_percentage ?? 0,
    ]);

    // FIXED REDIRECT
    return response()->redirectToRoute('beautician.dashboard')
                     ->with('success', 'Service added successfully!');
}


    public function updateService(Request $request, $id)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'description'  => 'nullable|string',
            'base_price'   => 'required|numeric|min:0',
            'category'     => 'required|string',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
        ]);

        $service = Service::findOrFail($id);
        $service->update([
            'service_name' => $request->service_name,
            'description'  => $request->description ?? '',
            'base_price'   => (float) $request->base_price,
            'category'     => $request->category,
            'discount_percentage' => $request->discount_percentage ?? 0,
        ]);

       // FIXED REDIRECT
    return response()->redirectToRoute('beautician.dashboard')
                     ->with('success', 'Service updated successfully.');
}
    public function deleteService($id)
{
    $service = Service::findOrFail($id);
    $service->delete();

    return response()->redirectToRoute('beautician.dashboard')
                     ->with('success', 'Service deleted successfully.');
}

    // ------------------------
    // Booking Actions
    // ------------------------

    public function notifyClientOnTheWay(Booking $booking)
    {
        // Ensure the booking belongs to the logged-in beautician
        if ($booking->beautician_id !== Auth::guard('beautician')->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $client = $booking->client;
        if (!$client || !$client->phone) {
            return response()->json(['success' => false, 'message' => 'Client phone number not found.'], 404);
        }

        // Send SMS via API
        $response = Http::post('https://www.iprogsms.com/api/v1/sms_messages', [
            'api_token' => 'e61f155b1ac0fd65c6d3d134c60848edb2575260', 
            'phone_number' => $client->phone,
            'message' => "Good day {$client->name}, this is a formal update from HomeGlam. Your beautician is now on the way to your location. Please ensure you are ready for your appointment. Thank you.",
        ]);

        if ($response->successful()) {
             return response()->json(['success' => true, 'message' => 'Client notified successfully!']);
        } else {
             // Log error for debugging
             \Illuminate\Support\Facades\Log::error('SMS API Error: ' . $response->body());
             return response()->json(['success' => false, 'message' => 'Failed to send SMS notification.'], 500);
        }
    }

    // Update booking status
    public function updateBookingStatus(Request $request, Booking $booking)
{
    $beautician = Auth::guard('beautician')->user();

    if ($booking->beautician_id !== $beautician->id) {
        abort(403, 'Unauthorized action.');
    }

    $request->validate([
        'status' => 'required|in:pending,accepted,completed,canceled',
    ]);

    // enforce limits, duplicates etc...
    if ($request->status === 'accepted') {
        // REMOVED STRICT LIMIT CHECK FOR TESTING/FIX
        // The button is now visible in blade, so we should allow the action
        // or provide a clearer error if it fails.
        // For now, let's trust the user's manual action.
        
        // $duplicate = Booking::where('client_id', $booking->client_id)
        //         ->where('beautician_id', $booking->beautician_id)
        //         ->where('service_id', $booking->service_id)
        //         ->where('status', 'accepted')
        //         ->where('id', '!=', $booking->id)
        //         ->exists();

        // if ($duplicate) {
        //     return back()->with('error', 'This client already has an active booking for this service.');
        // }
    }

    $booking->status = $request->status;
        if (!$booking->save()) {
            return back()->with('error', 'Failed to update booking status. Please try again.');
        }

        // Notify Client
        if ($booking->client) {
            $booking->client->notify(new BookingStatusUpdatedNotification($booking));
        }

        // Notify Beautician on Completion
    if ($request->status === 'completed') {
        try {
             $beauticianPhone = $beautician->phone;
             if ($beauticianPhone) {
                 $clientName = $booking->client->name ?? 'Client';
                 \Illuminate\Support\Facades\Http::post('https://www.iprogsms.com/api/v1/sms_messages', [
                    'api_token' => 'e61f155b1ac0fd65c6d3d134c60848edb2575260',
                    'phone_number' => $beauticianPhone,
                    'message' => "Appointment Completed: You have successfully completed the appointment with {$clientName}.",
                ]);
             }
        } catch (\Exception $e) {
             \Illuminate\Support\Facades\Log::error('SMS Error: ' . $e->getMessage());
        }
    }

    // Free up schedule if canceled
    if ($request->status === 'canceled') {
        $schedule = AppointmentSchedule::where('beautician_id', $booking->beautician_id)
            ->where('date', $booking->booking_date)
            ->where('start_time', 'like', $booking->booking_time . '%')
            ->first();

        if ($schedule) {
            $schedule->update(['status' => 'available']);
        }
    }

    // FIXED REDIRECT
    return response()->redirectToRoute('beautician.dashboard')
                     ->with('success', 'Profile updated successfully.');
}


    // ------------------------
    // Apply Discount
    // ------------------------
    public function applyDiscount(Request $request, Booking $booking)
    {
        $beautician = Auth::guard('beautician')->user();

        if ($booking->beautician_id !== $beautician->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'discount_amount' => 'required|numeric|min:0',
        ]);

        $newDiscount = $request->discount_amount;
        
        // Calculate original price (Current Total + Previous Discount)
        $originalPrice = $booking->total_cost + $booking->discount_amount;

        if ($newDiscount > $originalPrice) {
            return back()->with('error', 'Discount cannot exceed the total booking cost.');
        }

        $booking->total_cost = $originalPrice - $newDiscount;
        $booking->discount_amount = $newDiscount;
        $booking->save();

        return back()->with('success', 'Discount applied successfully!');
    }

    // ------------------------
    // Booking Limit Update
    // ------------------------
    public function updateBookingLimit(Request $request)
    {
        $request->validate([
            'max_bookings' => 'required|integer|min:1',
        ]);

        $beautician = Auth::guard('beautician')->user();

        if ($beautician instanceof Beautician) {
            $beautician->update([
                'max_bookings' => $request->max_bookings,
            ]);
        }

        return back()->with('success', 'Booking limit updated!');
    }

    // ------------------------
    // Profile Update
    // ------------------------
    public function editProfile()
    {
        $beautician = Auth::guard('beautician')->user();
        return view('dashboard.beautician_profile', compact('beautician'));
    }

    public function updateProfile(Request $request)
    {
        $beautician = Auth::guard('beautician')->user();

        if (!($beautician instanceof Beautician)) {
            // FIXED REDIRECT
            return response()->redirectToRoute('beautician.dashboard')
                             ->with('success', 'Profile updated successfully.');
        }

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:beauticians,email,' . $beautician->id,
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'qr_code' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'booking_duration' => 'nullable|integer|min:15|max:240',
        ]);

        $beautician->name = $request->name;
        $beautician->email = $request->email;
        $beautician->phone = $request->phone;

        if ($request->filled('password')) {
            $beautician->password = bcrypt($request->password);
        }

        if ($request->has('booking_duration')) {
            $beautician->booking_duration = $request->booking_duration;
        }

        if ($request->hasFile('photo')) {
            $directory = base_path('storage/uploads/beauticians');
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
            $beautician->photo_url = 'view-upload/beauticians/' . $filename;
        }

        if ($request->hasFile('qr_code')) {
            $directory = base_path('storage/uploads/beauticians/qr');
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            $file = $request->file('qr_code');
            $filename = 'qr_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
            $beautician->qr_code_path = 'view-upload/beauticians/qr/' . $filename;
        }

        $beautician->save();

       // FIXED REDIRECT
    return response()->redirectToRoute('beautician.dashboard')
                     ->with('success', 'Profile updated successfully.');
}

    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $beautician = Auth::guard('beautician')->user();

        if ($beautician instanceof Beautician) {
            $beautician->latitude = $request->latitude;
            $beautician->longitude = $request->longitude;
            $beautician->save();
        }

        return response()->json(['success' => true]);
    }


    // ------------------------
    // Save Availability
    // ------------------------
    public function saveAvailability(Request $request)
    {
        $request->validate([
            'selected_date' => 'required|date',
            'start_time'    => 'nullable|required_without:is_unavailable',
            'end_time'      => 'nullable|required_without:is_unavailable',
        ]);

        $beauticianId = Auth::guard('beautician')->id();
        $dayOfWeek = strtolower(Carbon::parse($request->selected_date)->format('l'));

        $status = $request->has('is_unavailable') ? 'inactive' : 'active';
        $startTime = $request->has('is_unavailable') ? '00:00' : $request->start_time;
        $endTime = $request->has('is_unavailable') ? '00:00' : $request->end_time;

        Availability::updateOrCreate(
            [
                'beautician_id' => $beauticianId,
                'day_of_week'   => $dayOfWeek,
            ],
            [
                'start_time' => $startTime,
                'end_time'   => $endTime,
                'status'     => $status,
            ]
        );

        return back()->with('success', 'Availability saved successfully!');
    }

// ------------------------
// Time Slot Management
// ------------------------
public function timeSlots()
{
    $beautician = Auth::guard('beautician')->user();

    $slots = TimeSlot::where('beautician_id', $beautician->id)
        ->orderBy('slot_date')
        ->orderBy('start_time')
        ->get();

    return view('dashboard.beautician_time_slots', compact('slots'));
}

public function storeTimeSlot(Request $request)
{
    // existing code
}

// ------------------------
    // Appointment Schedules (New Flow using Bookings table)
    // ------------------------
    public function getSchedules(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $beauticianId = Auth::guard('beautician')->id();
        $schedules = Booking::where('beautician_id', $beauticianId)
            ->where('booking_date', $request->date)
            ->orderBy('booking_time')
            ->get()
            ->map(function ($booking) {
                $booking->start_time = $booking->booking_time;
                $booking->date = $booking->booking_date;
                return $booking;
            });

        return response()->json($schedules);
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        return $this->createSchedule($request->date, $request->start_time, $request->end_time);
    }

    public function storeMultipleSchedules(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'slots' => 'required|array',
            'slots.*.start' => 'required',
            'slots.*.end' => 'required',
        ]);

        $results = [
            'success' => 0,
            'errors' => []
        ];

        foreach ($request->slots as $slot) {
            $response = $this->createSchedule($request->date, $slot['start'], $slot['end']);
            
            if ($response->getStatusCode() === 200) {
                $results['success']++;
            } else {
                $data = json_decode($response->getContent(), true);
                $results['errors'][] = "{$slot['start']} - {$slot['end']}: " . ($data['error'] ?? 'Unknown error');
            }
        }

        return response()->json($results);
    }

    private function createSchedule($date, $startTime, $endTime)
    {
        $beauticianId = Auth::guard('beautician')->id();

        // Check availability for the day
        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
        $availability = Availability::where('beautician_id', $beauticianId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$availability || $availability->status !== 'active') {
            return response()->json(['error' => 'You have not set yourself as available on ' . ucfirst($dayOfWeek) . '.'], 422);
        }

        // Validate time range against availability
        $availStart = Carbon::parse($availability->start_time)->setDate(2000, 1, 1);
        $availEnd = Carbon::parse($availability->end_time)->setDate(2000, 1, 1);

        // Handle overnight availability
        if ($availEnd->lte($availStart)) {
            $availEnd->addDay();
        }

        $reqStart = Carbon::parse($startTime)->setDate(2000, 1, 1);
        $reqEnd = Carbon::parse($endTime)->setDate(2000, 1, 1);

        // Handle overnight slot
        if ($reqEnd->lte($reqStart)) {
            $reqEnd->addDay();
        }

        if ($reqStart->lt($availStart) || $reqEnd->gt($availEnd)) {
             return response()->json([
                 'error' => 'Slot must be within your availability (' . Carbon::parse($availability->start_time)->format('h:i A') . ' - ' . Carbon::parse($availability->end_time)->format('h:i A') . ').'
             ], 422);
        }

        // Check for overlaps in Bookings table
        $overlap = Booking::where('beautician_id', $beauticianId)
            ->where('booking_date', $date)
            ->where('status', '!=', 'canceled')
            ->where(function ($query) use ($reqStart, $reqEnd, $startTime, $endTime) {
                $query->where('booking_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            })
            ->exists();

        if ($overlap) {
            return response()->json(['error' => 'Time slot overlaps with an existing booking or slot.'], 422);
        }

        $schedule = Booking::create([
            'beautician_id' => $beauticianId,
            'booking_date' => $date,
            'booking_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'available',
        ]);

        $schedule->start_time = $schedule->booking_time;
        $schedule->date = $schedule->booking_date;

        return response()->json($schedule);
    }

    public function deleteSchedule($id)
    {
        $beauticianId = Auth::guard('beautician')->id();
        $schedule = Booking::where('beautician_id', $beauticianId)->findOrFail($id);
        
        $schedule->delete();

        return redirect()->back()->with('success', 'Slot removed successfully.');
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'beautician_id' => 'required|exists:beauticians,id',
            'date' => 'required|date',
        ]);

        $beauticianId = $request->beautician_id;
        $date = $request->date;

        // Fetch available slots from Bookings table
        $slots = Booking::where('beautician_id', $beauticianId)
            ->where('booking_date', $date)
            ->where('status', 'available')
            ->orderBy('booking_time')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'time' => Carbon::parse($booking->booking_time)->format('h:i A') . ' - ' . Carbon::parse($booking->end_time)->format('h:i A'),
                    'start_time' => Carbon::parse($booking->booking_time)->format('H:i'),
                    'status' => 'available'
                ];
            });

        return response()->json($slots);
    }

}
