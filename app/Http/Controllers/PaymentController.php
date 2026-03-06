<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function success(Request $request)
    {
        $bookingId = $request->query('booking_id');
        
        if (!$bookingId) {
            return redirect()->route('client.dashboard')->with('error', 'Invalid payment return url.');
        }

        $booking = Booking::find($bookingId);
        if (!$booking) {
            return redirect()->route('client.dashboard')->with('error', 'Booking not found.');
        }

        // Update booking status
        $booking->update([
            'payment_status' => 'paid',
            // 'down_payment_amount' is already set in ClientController before payment
        ]);

        return redirect()->route('client.dashboard')->with('success', 'Payment successful! Your booking is now pending beautician approval.');
    }

    public function cancel(Request $request)
    {
        $bookingId = $request->query('booking_id');
        
        if ($bookingId) {
            $booking = Booking::find($bookingId);
            if ($booking) {
                // Mark as failed/canceled
                $booking->update([
                    'payment_status' => 'failed', 
                    // 'status' => 'canceled'
                ]);
            }
        }

        return redirect()->route('client.dashboard')->with('error', 'Payment was canceled.');
    }
}
