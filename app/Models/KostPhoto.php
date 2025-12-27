<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KostPhoto extends Model
{
    use HasFactory;

    // Tentukan tabel jika nama tabel tidak mengikuti konvensi pluralisasi Laravel
    protected $table = 'kost_photos';

    // Tentukan kolom yang dapat diisi secara massal (fillable)
    protected $fillable = [
        'kost_id',
        'path',
    ];

    // Relasi dengan model Kost (one-to-many)
    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }
}
