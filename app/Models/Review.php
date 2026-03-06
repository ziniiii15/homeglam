<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id','client_id','beautician_id','rating','comment','date', 'image_url'];

    public function booking() { return $this->belongsTo(Booking::class); }
    public function client() { return $this->belongsTo(Client::class); }
    public function beautician() { return $this->belongsTo(Beautician::class); }
}
