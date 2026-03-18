<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Beautician;
use App\Models\Service;
use App\Notifications\BookingStatusUpdatedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    // ------------------------
    // Admin: List all bookings
    // ------------------------
    public function index()
    {
        $bookings = Booking::with(['client','beautician','service'])->get();
        return view('bookings.index', compact('bookings'));
    }

    // ------------------------
    // Admin: Show booking creation form
    // ------------------------
    public function create()
    {
        $clients = Client::all();
        $beauticians = Beautician::all();
        $services = Service::all();
        return view('bookings.create', compact('clients','beauticians','services'));
    }

    // ------------------------
    // Client: Store booking (DUPLICATE-SAFE)
    // ------------------------
    public function store(Request $request)
    {
        if (!Auth::guard('client')->check()) {
            return redirect()->route('client.login')
                ->with('error', 'Please log in as a client.');
        }

        $validated = $request->validate([
            'service_id'     => 'required|exists:services,id',
            'beautician_id'  => 'required|exists:beauticians,id',
            'booking_date'   => 'required|date|after_or_equal:today',
            'booking_time'   => 'required',
            'location'       => 'required|string|max:255',
            'client_notes'   => 'nullable|string|max:500',
        ]);

        $clientId = Auth::guard('client')->id();

        // Check for duplicate active bookings
        $duplicate = Booking::where('client_id', $clientId)
            ->where('beautician_id', $validated['beautician_id'])
            ->where('service_id', $validated['service_id'])
            ->where('booking_date', $validated['booking_date'])
            ->whereIn('status', ['pending','accepted'])
            ->exists();

        if ($duplicate) {
            return back()
                ->with('booking_exists', true)
                ->withInput();
        }

        $service = Service::findOrFail($validated['service_id']);
        $totalCost = $service->base_price;

        DB::beginTransaction();
        try {
            Booking::create([
                'client_id'     => $clientId,
                'beautician_id' => $validated['beautician_id'],
                'service_id'    => $validated['service_id'],
                'booking_date'  => $validated['booking_date'],
                'booking_time'  => $validated['booking_time'],
                'location'      => $validated['location'],
                'client_notes'  => $validated['client_notes'] ?? '',
                'status'        => 'pending',
                'total_cost'    => $totalCost,
            ]);

            DB::commit();
            return back()->with('success', 'Booking created successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Failed to create booking.')
                ->withInput();
        }
    }

    // ------------------------
    // Admin: Show booking
    // ------------------------
    public function show(Booking $booking)
    {
        $booking->load(['client','beautician','service','review']);
        return view('bookings.show', compact('booking'));
    }

    // ------------------------
    // Admin: Edit booking
    // ------------------------
    public function edit(Booking $booking)
    {
        $clients = Client::all();
        $beauticians = Beautician::all();
        $services = Service::all();
        return view('bookings.edit', compact('booking','clients','beauticians','services'));
    }

    // ------------------------
    // Admin: Update booking
    // ------------------------
    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'client_id'     => 'required|exists:clients,id',
            'beautician_id' => 'required|exists:beauticians,id',
            'service_id'    => 'required|exists:services,id',
            'booking_date'  => 'required|date',
            'booking_time'  => 'required',
            'location'      => 'required|string|max:255',
            'client_notes'  => 'nullable|string|max:500',
            'status'        => 'required|in:pending,completed,canceled',
        ]);

        $service = Service::findOrFail($validated['service_id']);

        $booking->update([
            'client_id'     => $validated['client_id'],
            'beautician_id' => $validated['beautician_id'],
            'service_id'    => $validated['service_id'],
            'booking_date'  => $validated['booking_date'],
            'booking_time'  => $validated['booking_time'],
            'location'      => $validated['location'],
            'client_notes'  => $validated['client_notes'] ?? '',
            'status'        => $validated['status'],
            'total_cost'    => $service->base_price,
        ]);

        return redirect()->route('bookings.index')
            ->with('success','Booking updated successfully.');
    }

    // ------------------------
    // Admin: Delete booking
    // ------------------------
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('bookings.index')
            ->with('success','Booking deleted successfully.');
    }

    // ------------------------
    // Client: Cancel booking
    // ------------------------
    public function cancel(Booking $booking)
    {
        if ($booking->status === 'pending') {
            $booking->update(['status' => 'canceled']);
        }

        return back()->with('success', 'Booking canceled successfully.');
    }

    // ------------------------
    // Get beauticians by category with correct availability
    // ------------------------
    public function getBeauticiansByCategory($category)
    {
        $beauticians = Beautician::whereHas('services', function($q) use ($category) {
            $q->where('category', $category);
        })->with(['services', 'availabilities'])->get();

        $now = Carbon::now('Asia/Manila'); // use correct timezone

        $beauticians->transform(function($b) use ($now) {
            $todayName = strtolower($now->format('l')); // 'sunday', etc.

            // Find today's active availability
            $availability = $b->availabilities
                ->where('status', 'active')
                ->firstWhere('day_of_week', strtolower($todayName));

            if ($availability) {
                // Combine with today’s date
                $start = Carbon::createFromFormat('Y-m-d H:i:s', $now->toDateString().' '.$availability->start_time, 'Asia/Manila');
                $end   = Carbon::createFromFormat('Y-m-d H:i:s', $now->toDateString().' '.$availability->end_time, 'Asia/Manila');

                // Handle overnight availability (end < start)
                if ($end->lessThanOrEqualTo($start)) {
                    $end->addDay();
                }

                // Check if now is within availability
                $b->is_available_today = $now->between($start, $end);
                $b->availability_time = $start->format('h:i A').' - '.$end->format('h:i A');

            } else {
                $b->is_available_today = false;
                $b->availability_time = null;
            }

            return $b;
        });

        return response()->json($beauticians);
    }
}

