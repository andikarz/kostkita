<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'kost_id',
        'tanggal_mulai',
        'lama_sewa',
        'harga_per_bulan',
        'pajak',
        'total',
        'status'
    ];

    public function kost()   { return $this->belongsTo(Kost::class); }
    public function user()   { return $this->belongsTo(User::class); }
    public function payment(){ return $this->hasOne(Payment::class); }
    public function rating() { return $this->hasOne(Rating::class); }
}

