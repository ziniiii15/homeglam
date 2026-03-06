<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'value',
        'valid_from',
        'valid_to',
        'service_id',
        'beautician_id'
    ];

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function beautician() {
        return $this->belongsTo(Beautician::class);
    }
}
