<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'beautician_id',
        'service_id',
        'booking_date',
        'booking_time',
        'end_time',
        'location',
        'total_cost',
        'amount_paid',
        'down_payment_amount',
        'discount_amount',
        'status',
        'payment_status',
        'payment_id',
        'payment_receipt_path',
        'transaction_id',
        'client_notes',
        'allergy_info',
    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function beautician()
    {
        return $this->belongsTo(Beautician::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function report()
    {
        return $this->hasOne(Report::class);
    }

    /**
     * Check for expired pending bookings and auto-refund/cancel them.
     * Should be called periodically (e.g. on dashboard load).
     */
    public static function checkExpiredBookings()
    {
        $now = \Carbon\Carbon::now();
        
        // Find pending bookings that are past their time
        // Added grace period: Don't cancel bookings updated in the last minute
        // This prevents race conditions where a booking is accepted just as the check runs
        $expiredBookings = self::where('status', 'pending')
            ->where('updated_at', '<', $now->copy()->subMinutes(1)) 
            ->where(function($query) use ($now) {
                $query->where('booking_date', '<', $now->toDateString())
                      ->orWhere(function($q) use ($now) {
                          $q->where('booking_date', '=', $now->toDateString())
                            ->where('booking_time', '<', $now->format('H:i:s'));
                      });
            })
            ->get();

        foreach ($expiredBookings as $booking) {
            $booking->status = 'canceled';
            $previousPaymentStatus = $booking->payment_status;

            if ($booking->payment_status === 'paid') {
                $booking->payment_status = 'refunded';
            } else {
                $booking->payment_status = 'canceled';
            }
            
            $booking->save();

            // Notify Client via SMS
            if ($booking->client && $booking->client->phone) {
                try {
                    $clientName = $booking->client->name;
                    $beauticianName = $booking->beautician ? $booking->beautician->name : 'Beautician';
                    $serviceName = $booking->service ? $booking->service->service_name : 'Service';
                    
                    $message = "Hi {$clientName}, your booking for {$serviceName} with {$beauticianName} was not accepted within the scheduled time. It has been automatically cancelled";
                    
                    if ($previousPaymentStatus === 'paid') {
                        $message .= " and refunded.";
                    } else {
                        $message .= ".";
                    }

                    \Illuminate\Support\Facades\Http::post('https://www.iprogsms.com/api/v1/sms_messages', [
                        'api_token' => 'e61f155b1ac0fd65c6d3d134c60848edb2575260',
                        'phone_number' => $booking->client->phone,
                        'message' => $message,
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Auto-Refund SMS Error: ' . $e->getMessage());
                }
            }
        }
    }
}
