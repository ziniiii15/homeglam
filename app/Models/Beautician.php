<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Beautician extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'base_location',
        'password',
        'photo_url',
        'max_bookings',
        'booking_duration',
        'banned_until',
        'verification_document',
        'is_verified',
        'verification_status',
        'rejection_reason',
        'rejected_at',
        'subscription_expires_at',
        'qr_code_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'banned_until' => 'datetime',
        'subscription_expires_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function serviceList()
    {
        return $this->hasMany(Service::class, 'beautician_id');
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class, 'beautician_id');
    }
}
