<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'beautician_id',
        'service_name',
        'description',
        'base_price',
        'category',
        'discount_percentage',
    ];

    // Relationship to bookings
    public function bookings() 
    { 
        return $this->hasMany(Booking::class); 
    }

    // Relationship to beautician
    public function beautician()
    {
        return $this->belongsTo(Beautician::class, 'beautician_id');
    }
}
