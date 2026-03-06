<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    // Hidden fields for arrays
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts (optional, for datetime fields)
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
