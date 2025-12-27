<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kost extends Model
{
    protected $fillable = [
        'owner_id',
        'nama',
        'jenis',
        'deskripsi',
        'alamat',
        'kecamatan',
        'kota',
        'harga_bulanan',
        'stok_kamar',
        'ukuran_kamar',
        'listrik_status',
        'fasilitas',
        'fasilitas_kmandi',
        'fasilitas_umum',
        'cover',
        'is_recommended',
    ];

    protected $casts = [
    'is_recommended'   => 'boolean',
    'fasilitas'        => 'array',
    'fasilitas_kmandi' => 'array',
    'fasilitas_umum'   => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function photos()
    {
        return $this->hasMany(KostPhoto::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

}
