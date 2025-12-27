<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'nik',
        'image',
        'image_id',
        'google_id',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function kosts()
    {
        // kost yang dimiliki
        return $this->hasMany(Kost::class, 'owner_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

