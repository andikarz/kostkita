<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'jumlah',
        'metode',  // added based on migration
        'status',
        'snap_token',
        'transaction_id',
        'payment_type',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

