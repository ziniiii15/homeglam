<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = ['beautician_id', 'image_url', 'description'];

    public function beautician()
    {
        return $this->belongsTo(Beautician::class);
    }
}
