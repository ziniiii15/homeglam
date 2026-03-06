<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Beautician;

class Availability extends Model
{
    use HasFactory;

    protected $table = 'availabilities'; // ensure Laravel uses the correct table

    protected $fillable = [
        'beautician_id',
        'day_of_week',
        'start_time',
        'end_time',
        'status'
    ];

    public function beautician()
    {
        return $this->belongsTo(Beautician::class, 'beautician_id');
    }
}
