<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'beautician_id',
        'slot_date',
        'start_time',
        'end_time',
        'is_booked',
    ];

    public function beautician()
    {
        return $this->belongsTo(Beautician::class);
    }
}
