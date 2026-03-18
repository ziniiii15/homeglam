<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Client;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Beautician;
use App\Models\AppointmentSchedule;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\NewBookingNotification;
use App\Notifications\BookingStatusUpdatedNotification;

class ClientController extends Controller
{
    // -----------------------------
    // Resource Methods
    // -----------------------------
    public function index()
    {
        $clients = Client::all();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:clients',
            'phone' => 'required',
            'address' => 'required',
            'password' => 'required|min:6',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'address']);
        $data['password'] = bcrypt($request->password);

        if ($request->hasFile('photo')) {
            $directory = base_path('storage/uploads/clients');
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            $filename = time() . '.' . $request->photo->extension();
            $request->photo->move($directory, $filename);
            $data['photo_url'] = 'view-upload/clients/' . $filename;
        }

        Client::create($data);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        $client->load(['bookings.beautician', 'reviews.beautician']);
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'required',
            'address' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $client->name = $request->name;
        $client->email = $request->email;
        $client->phone = $request->phone;
        $client->address = $request->address;

        if ($request->hasFile('photo')) {
            $directory = base_path('storage/uploads/clients');
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            $filename = time() . '.' . $request->photo->extension();
            $request->photo->move($directory, $filename);
            $client->photo_url = 'view-upload/clients/' . $filename;
        }

        $client->save();

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }

    // -----------------------------
    // Client Dashboard
    // -----------------------------
    public function dashboard()
    {
        // Check for expired bookings
        Booking::checkExpiredBookings();

        $client = Auth::guard('client')->user();

        if (!$client) {
            return redirect()->route('client.login')->with('error', 'Please log in first.');
        }

        $bookings = Booking::with(['service', 'beautician', 'review'])
            ->where('client_id', $client->id)
            ->orderByRaw("FIELD(status, 'pending', 'finished', 'canceled')")
            ->orderBy('booking_date', 'asc')
            
            ->get()
            ->map(function ($booking) {
                $booking->status = strtolower($booking->status);
                return $booking;
            });

        $services = Service::with('beautician')->get();

        $categories = Category::all();

        $beauticians = Beautician::with(['serviceList', 'galleries'])->get()->map(function($b) {
            return [
                'id' => $b->id,
                'name' => $b->name,
                'photo_url' => $b->photo_url ?? 'https://via.placeholder.com/80',
                'base_location' => $b->base_location,
                'latitude' => $b->latitude,
                'longitude' => $b->longitude,
                'qr_code_path' => $b->qr_code_path ? asset($b->qr_code_path) : null,
                'services' => $b->getRelation('serviceList'),
                'gallery' => $b->getRelation('galleries'),
            ];
        });

        return view('dashboard.client', compact('bookings', 'services', 'beauticians', 'client', 'categories'));
    }

    public function transactions()
    {
        $client = Auth::guard('client')->user();

        if (!$client) {
            return redirect()->route('client.login')->with('error', 'Please log in first.');
        }

        $bookings = Booking::with(['service', 'beautician'])
            ->where('client_id', $client->id)
            ->whereNotNull('payment_status')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.client-transactions', compact('bookings'));
    }

    // -----------------------------
    // Store Booking
    // -----------------------------
    public function store_booking(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'beautician_id' => 'required|exists:beauticians,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'location' => 'required|string',
            'client_notes' => 'nullable|string|max:500',
            'allergy_info' => 'nullable|string|max:255',
            'slot_id' => 'nullable|exists:bookings,id',
            'travel_fee' => 'nullable|numeric|min:0',
        ]);

        $service = Service::findOrFail($request->service_id);
        
        // Calculate discounted price
        $price = $service->base_price;
        $discountAmount = 0;
        
        if ($service->discount_percentage > 0) {
            $discountedPrice = $service->base_price * (1 - $service->discount_percentage / 100);
            $discountAmount = $service->base_price - $discountedPrice;
            $price = $discountedPrice;
        }

        $travelFee = $request->input('travel_fee', 0);
        $totalCost = $price + $travelFee;

        // Prepare notes
        $finalNotes = $request->client_notes ?? '';
        if ($travelFee > 0) {
            $finalNotes .= "\n(Includes Travel Fee: ₱" . number_format($travelFee, 2) . ")";
        }

        // Verify service belongs to beautician
        if ($service->beautician_id != $request->beautician_id) {
             return back()->with('error', 'Service does not belong to the selected beautician.');
        }

        $slot = null;
        if ($request->filled('slot_id')) {
            $slot = Booking::find($request->slot_id);
            // Verify slot is available
            if ($slot && $slot->status !== 'available') {
                return back()->with('error', 'This slot is no longer available.')
                             ->with('booking_exists', true);
            }
        } else {
            // Try to find an available slot matching the time (fallback)
            $slot = Booking::where('beautician_id', $request->beautician_id)
                ->where('booking_date', $request->booking_date)
                ->where('booking_time', $request->booking_time)
                ->where('status', 'available')
                ->first();
        }

        $booking = null;

        if ($slot) {
            $downPaymentAmount = $totalCost * 0.50;

            // Update the slot to become a booking
            $slot->update([
                'client_id' => Auth::guard('client')->id(),
                'service_id' => $service->id,
                'location' => $request->location,
                'client_notes' => $finalNotes,
                'allergy_info' => $request->allergy_info,
                'status' => 'pending',
                'total_cost' => $totalCost,
                'payment_status' => 'pending',
                'down_payment_amount' => $downPaymentAmount,
                'discount_amount' => $discountAmount,
            ]);
            $booking = $slot;
        } else {
            // Check double booking for ad-hoc request
            $exists = Booking::where('beautician_id', $request->beautician_id)
                ->where('booking_date', $request->booking_date)
                ->where('booking_time', $request->booking_time)
                ->whereIn('status', ['pending', 'accepted'])
                ->exists();
                
            if ($exists) {
                 return back()->with('error', 'This time is already booked.')
                              ->with('booking_exists', true);
            }
            
            $downPaymentAmount = $totalCost * 0.50;
            
            $booking = Booking::create([
                'client_id' => Auth::guard('client')->id(),
                'beautician_id' => $request->beautician_id,
                'service_id' => $service->id,
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'end_time' => Carbon::parse($request->booking_time)->addHour()->format('H:i'), // Default 1 hour duration
                'location' => $request->location,
                'client_notes' => $finalNotes,
                'allergy_info' => $request->allergy_info,
                'status' => 'pending',
                'total_cost' => $totalCost,
                'payment_status' => 'pending',
                'down_payment_amount' => $downPaymentAmount,
                'discount_amount' => $discountAmount,
            ]);
        }

        // --- Notify Beautician (New Appointment) ---
        try {
            $beautician = \App\Models\Beautician::find($request->beautician_id);
            if ($beautician) {
                $beautician->notify(new NewBookingNotification($booking));
            }

            if ($beautician && $beautician->phone) {
                $clientName = Auth::guard('client')->user()->name;
                $serviceName = $service->service_name;
                $date = $request->booking_date;
                $time = $request->booking_time;
                
                \Illuminate\Support\Facades\Http::post('https://www.iprogsms.com/api/v1/sms_messages', [
                    'api_token' => 'e61f155b1ac0fd65c6d3d134c60848edb2575260',
                    'phone_number' => $beautician->phone,
                    'message' => "New Booking from {$clientName}: {$serviceName} on {$date} at {$time}. Please check your HomeGlam dashboard for details.",
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SMS Notification Error: ' . $e->getMessage());
        }

        // Check payment option
        if ($request->payment_option === 'pay_later') {
            // Reset downpayment amount to 0 since they haven't paid yet
            $booking->update(['down_payment_amount' => 0]);
            return redirect()->route('client.dashboard')->with('success', 'Booking request sent! You can pay the downpayment later.');
        }

        // Determine payment amount and label
        $paymentAmount = $totalCost * 0.50;
        $paymentLabel = "Down Payment for " . $service->service_name;

        if ($request->payment_option === 'pay_full') {
            $paymentAmount = $totalCost;
            $paymentLabel = "Full Payment for " . $service->service_name;
        }

        // Update booking with the intended payment amount
        $booking->update(['down_payment_amount' => $paymentAmount]);

        // --- Payment Receipt Upload (Manual Payment) ---
        if ($request->hasFile('payment_receipt')) {
            $file = $request->file('payment_receipt');
            $filename = 'receipt_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(base_path('storage/uploads/bookings/receipts'), $filename);
            
            $booking->update([
                'payment_receipt_path' => 'view-upload/bookings/receipts/' . $filename,
                'payment_status' => 'pending_verification' // Or keep 'pending'
            ]);

            return redirect()->route('client.dashboard')->with('success', 'Booking submitted with payment receipt! Waiting for beautician confirmation.');
        }

        // --- PayMongo Integration ---
        try {
            $amountInCents = intval($paymentAmount * 100);

            $data = [
                "data" => [
                    "attributes" => [
                        "line_items" => [
                            [
                                "name" => $paymentLabel,
                                "amount" => $amountInCents,
                                "currency" => "PHP",
                                "quantity" => 1
                            ]
                        ],
                        "billing" => [
                            "name" => Auth::guard('client')->user()->name,
                            "email" => Auth::guard('client')->user()->email,
                            "phone" => Auth::guard('client')->user()->phone,
                            "address" => [
                                "line1" => Auth::guard('client')->user()->address,
                            ]
                        ],
                        "payment_method_types" => ["card", "gcash", "paymaya", "grab_pay", "dob", "billease"],
                        "description" => $paymentLabel,
                        "success_url" => route('payment.success', ['booking_id' => $booking->id]),
                        "cancel_url" => route('payment.cancel', ['booking_id' => $booking->id])
                    ]
                ]
            ];

            $secretKey = "sk_live_Jdh8htgqxH5NtrVpRMcSpWk3"; 
            $publicKey = "pk_live_G9CKr8xgLrS5wRx127o1D3RQ";

            $response = \Illuminate\Support\Facades\Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($secretKey . ':')
            ])->post('https://api.paymongo.com/v1/checkout_sessions', $data);

            if ($response->failed()) {
                return back()->with('error', 'Payment connection failed: ' . $response->body());
            }

            $result = $response->json();
            
            if (isset($result['data']['attributes']['checkout_url'])) {
                // Save checkout session ID
                $booking->update(['payment_id' => $result['data']['id']]);
                return redirect($result['data']['attributes']['checkout_url']);
            } else {
                \Illuminate\Support\Facades\Log::error('PayMongo Error', ['response' => $result]);
                return back()->with('error', 'Could not initiate payment. Please try again later.');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Payment Exception', ['message' => $e->getMessage()]);
            return back()->with('error', 'An error occurred during payment processing.');
        }
    }


    // -----------------------------
    // Cancel Booking
    // -----------------------------
    public function cancelBooking(Request $request, Booking $booking)
    {
        $client = Auth::guard('client')->user();

        if (!$client || $booking->client_id !== $client->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status === 'pending') {
            $booking->status = 'canceled';
            $booking->save();

            // Notify Beautician
            if ($booking->beautician) {
                $booking->beautician->notify(new BookingStatusUpdatedNotification($booking));
            }

            /* 
             * User Request: "make it so when i cancel the appointment, it wont remove the slot or cancle it"
             * Interpretation: Do not automatically re-create/open the slot. Keep it blocked/consumed.
             */
            // Re-create the available slot so others can book
            // Booking::create([
            //     'beautician_id' => $booking->beautician_id,
            //     'booking_date' => $booking->booking_date,
            //     'booking_time' => $booking->booking_time,
            //     'end_time' => $booking->end_time,
            //     'status' => 'available',
            // ]);
        }
        
        return back()->with('success', 'Booking canceled.');
    }

    // -----------------------------
    // Filter Beauticians by Service (AJAX)
    // -----------------------------
    public function getBeauticiansByService($serviceName)
    {
        $beauticians = Beautician::with(['serviceList' => function($q) use ($serviceName) {
                $q->where('service_name', 'LIKE', '%' . $serviceName . '%');
            }, 'galleries', 'reviews.client', 'availabilities'])
            ->withAvg('reviews', 'rating')
            ->whereHas('serviceList', function($q) use ($serviceName) {
                $q->where('service_name', 'LIKE', '%' . $serviceName . '%');
            })
            ->get();

        $client = Auth::guard('client')->user();

        $hasClientLocation = $client && $client->latitude !== null && $client->longitude !== null;

        $clientLat = $hasClientLocation ? (float) $client->latitude : null;
        $clientLng = $hasClientLocation ? (float) $client->longitude : null;

        $distance = null;

        if ($hasClientLocation) {
            $distance = function (float $lat1, float $lng1, float $lat2, float $lng2): float {
                $earthRadius = 6371;
                $dLat = deg2rad($lat2 - $lat1);
                $dLng = deg2rad($lng2 - $lng1);
                $a = sin($dLat / 2) * sin($dLat / 2) +
                    cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                    sin($dLng / 2) * sin($dLng / 2);
                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                return $earthRadius * $c;
            };
        }

        $beauticians = $beauticians->sort(function ($a, $b) use ($hasClientLocation, $clientLat, $clientLng, $distance) {
            $ratingA = $a->reviews_avg_rating !== null ? (float) $a->reviews_avg_rating : 0.0;
            $ratingB = $b->reviews_avg_rating !== null ? (float) $b->reviews_avg_rating : 0.0;

            if ($ratingA !== $ratingB) {
                return $ratingA > $ratingB ? -1 : 1;
            }

            if (!$hasClientLocation || $distance === null || $clientLat === null || $clientLng === null) {
                return 0;
            }

            $aHasCoords = $a->latitude !== null && $a->longitude !== null;
            $bHasCoords = $b->latitude !== null && $b->longitude !== null;

            $distA = $aHasCoords
                ? $distance($clientLat, $clientLng, (float) $a->latitude, (float) $a->longitude)
                : PHP_FLOAT_MAX;
            $distB = $bHasCoords
                ? $distance($clientLat, $clientLng, (float) $b->latitude, (float) $b->longitude)
                : PHP_FLOAT_MAX;

            if ($distA === $distB) {
                return 0;
            }

            return $distA < $distB ? -1 : 1;
        })->values();

        return response()->json(
            $beauticians->map(function($b) {
                return [
                    'id' => $b->id,
                    'name' => $b->name,
                    'email' => $b->email,
                    'phone' => $b->phone,
                    'base_location' => $b->base_location,
                    'latitude' => $b->latitude,
                    'longitude' => $b->longitude,
                    'qr_code_path' => $b->qr_code_path ? asset($b->qr_code_path) : null,
                    'photo_url' => $b->photo_url ? asset($b->photo_url) : null,
                    'rating_avg' => $b->reviews_avg_rating ? round($b->reviews_avg_rating, 1) : null,
                    'availabilities' => $b->getRelation('availabilities')->values(),
                    'services' => $b->getRelation('serviceList')->map(function($s) use ($b){
                        return [
                            'id' => $s->id,
                            'service_name' => $s->service_name,
                            'category' => $s->category,
                            'base_price' => $s->base_price,
                            'discount_percentage' => $s->discount_percentage,
                            'duration_minutes' => $b->booking_duration,
                        ];
                    }),
                    'gallery' => $b->getRelation('galleries')->map(function($g) {
                        return [
                            'image_url' => asset($g->image_url),
                            'description' => $g->description
                        ];
                    })->values(),
                    'reviews' => $b->getRelation('reviews')->map(function($r) {
                        return [
                            'id' => $r->id,
                            'rating' => $r->rating,
                            'comment' => $r->comment,
                            'image_url' => $r->image_url ? asset($r->image_url) : null,
                            'created_at' => $r->created_at->diffForHumans(),
                            'client_name' => $r->client ? $r->client->name : 'Anonymous'
                        ];
                    })->values(),
                ];
            })
        );
    }

    // -----------------------------
    // Filter Beauticians by Category (AJAX)
    // -----------------------------
    public function getBeauticiansByCategory($category)
    {
        // Fetch beauticians with services in the category and ALL availabilities
        $beauticians = Beautician::with([
            'serviceList' => fn($q) =>
                $q->whereRaw('LOWER(category) = ?', [strtolower($category)]),
            'availabilities', // Load all availabilities
            'galleries', // Load galleries
            'reviews.client' // Load reviews with client
        ])
            ->withAvg('reviews', 'rating')
            ->whereHas('serviceList', fn($q) =>
                $q->whereRaw('LOWER(category) = ?', [strtolower($category)])
            )
            ->get();

        $client = Auth::guard('client')->user();

        $hasClientLocation = $client && $client->latitude !== null && $client->longitude !== null;

        $clientLat = $hasClientLocation ? (float) $client->latitude : null;
        $clientLng = $hasClientLocation ? (float) $client->longitude : null;

        $distance = null;

        if ($hasClientLocation) {
            $distance = function (float $lat1, float $lng1, float $lat2, float $lng2): float {
                $earthRadius = 6371;
                $dLat = deg2rad($lat2 - $lat1);
                $dLng = deg2rad($lng2 - $lng1);
                $a = sin($dLat / 2) * sin($dLat / 2) +
                    cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                    sin($dLng / 2) * sin($dLng / 2);
                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                return $earthRadius * $c;
            };
        }

        $beauticians = $beauticians->sort(function ($a, $b) use ($hasClientLocation, $clientLat, $clientLng, $distance) {
            $ratingA = $a->reviews_avg_rating !== null ? (float) $a->reviews_avg_rating : 0.0;
            $ratingB = $b->reviews_avg_rating !== null ? (float) $b->reviews_avg_rating : 0.0;

            if ($ratingA !== $ratingB) {
                return $ratingA > $ratingB ? -1 : 1;
            }

            if (!$hasClientLocation || $distance === null || $clientLat === null || $clientLng === null) {
                return 0;
            }

            $aHasCoords = $a->latitude !== null && $a->longitude !== null;
            $bHasCoords = $b->latitude !== null && $b->longitude !== null;

            $distA = $aHasCoords
                ? $distance($clientLat, $clientLng, (float) $a->latitude, (float) $a->longitude)
                : PHP_FLOAT_MAX;
            $distB = $bHasCoords
                ? $distance($clientLat, $clientLng, (float) $b->latitude, (float) $b->longitude)
                : PHP_FLOAT_MAX;

            if ($distA === $distB) {
                return 0;
            }

            return $distA < $distB ? -1 : 1;
        })->values();

        return response()->json(
            $beauticians->map(function ($b) {
                return [
                    'id' => $b->id,
                    'name' => $b->name,
                    'email' => $b->email,
                    'phone' => $b->phone,
                    'base_location' => $b->base_location,
                    'latitude' => $b->latitude,
                    'longitude' => $b->longitude,
                    'qr_code_path' => $b->qr_code_path ? asset($b->qr_code_path) : null,
                    'photo_url' => $b->photo_url ? asset($b->photo_url) : null,
                    'rating_avg' => $b->reviews_avg_rating ? round($b->reviews_avg_rating, 1) : null,
                    'services' => $b->getRelation('serviceList')->map(function($s) use ($b) {
                        return [
                            'id' => $s->id,
                            'service_name' => $s->service_name,
                            'category' => $s->category,
                            'base_price' => $s->base_price,
                            'discount_percentage' => $s->discount_percentage,
                            'duration_minutes' => $b->booking_duration,
                        ];
                    })->values(),
                    'availabilities' => $b->getRelation('availabilities')->values(),
                    'gallery' => $b->getRelation('galleries')->map(function($g) {
                        return [
                            'image_url' => asset($g->image_url),
                            'description' => $g->description
                        ];
                    })->values(),
                    'reviews' => $b->getRelation('reviews')->map(function($r) {
                        return [
                            'id' => $r->id,
                            'rating' => $r->rating,
                            'comment' => $r->comment,
                            'image_url' => $r->image_url ? asset($r->image_url) : null,
                            'created_at' => $r->created_at->diffForHumans(),
                            'client_name' => $r->client ? $r->client->name : 'Anonymous'
                        ];
                    })->values(),
                ];
            })
        );
    }




    // -----------------------------
// Update Profile
// -----------------------------
public function updateProfile(Request $request)
{
    $clientId = Auth::guard('client')->id();

    if (!$clientId) {
        return new RedirectResponse('/client/login', 302, ['Content-Type' => 'text/html']);
    }

    $client = Client::find($clientId);

    if (!$client) {
        return new RedirectResponse('/client/login', 302, ['Content-Type' => 'text/html']);
    }

    // Validate input
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:clients,email,' . $client->id,
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'password' => 'nullable|min:6',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Update photo if uploaded
    if ($request->hasFile('photo')) {
        $filename = time() . '.' . $request->photo->extension();
        $request->photo->move(base_path('storage/uploads/clients'), $filename);
        $client->photo_url = 'view-upload/clients/' . $filename;
    }

    // Update fields
    $client->name = $request->name;
    $client->email = $request->email;
    $client->phone = $request->phone;
    $client->address = $request->address;

    // Update password only if filled
    if ($request->filled('password')) {
        $client->password = bcrypt($request->password);
    }

    // Save changes
    $client->save();

    // Redirect safely using RedirectResponse
    return new RedirectResponse('/client/dashboard', 302, ['Content-Type' => 'text/html']);
}

public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|string'
        ]);

        /** @var \App\Models\Client|null $client */
        $client = Auth::guard('client')->user();
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $client->latitude = $request->latitude;
    $client->longitude = $request->longitude;
    $client->address = $request->address;
    $client->save();

    return response()->json(['success' => true, 'message' => 'Location updated successfully']);
}
}

