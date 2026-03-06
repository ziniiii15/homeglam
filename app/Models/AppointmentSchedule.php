<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentSchedule extends Model
{
    protected $fillable = [
        'beautician_id',
        'date',
        'start_time',
        'end_time',
        'status',
    ];

    public function beautician()
    {
        return $this->belongsTo(Beautician::class);
    }
}
