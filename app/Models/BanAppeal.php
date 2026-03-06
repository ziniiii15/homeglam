<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BanAppeal extends Model
{
    protected $fillable = [
        'beautician_id',
        'reason',
        'proof_image',
        'status',
    ];

    public function beautician()
    {
        return $this->belongsTo(Beautician::class);
    }
}
