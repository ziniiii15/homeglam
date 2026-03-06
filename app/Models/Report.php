<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'client_id',
        'beautician_id',
        'reason',
        'proof_image',
        'status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function beautician()
    {
        return $this->belongsTo(Beautician::class);
    }
}
