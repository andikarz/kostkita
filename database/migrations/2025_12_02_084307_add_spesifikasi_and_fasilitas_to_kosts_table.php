<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kosts', function (Blueprint $table) {
            // Cek dulu, biar gak error kalau sudah ada
            if (!Schema::hasColumn('kosts', 'ukuran_kamar')) {
                $table->string('ukuran_kamar')->nullable()->after('stok_kamar');
            }
            if (!Schema::hasColumn('kosts', 'listrik_status')) {
                $table->string('listrik_status')->nullable()->after('ukuran_kamar');
            }
            if (!Schema::hasColumn('kosts', 'fasilitas')) {
                $table->json('fasilitas')->nullable()->after('deskripsi');
            }
            if (!Schema::hasColumn('kosts', 'fasilitas_kmandi')) {
                $table->json('fasilitas_kmandi')->nullable()->after('fasilitas');
            }
            if (!Schema::hasColumn('kosts', 'fasilitas_umum')) {
                $table->json('fasilitas_umum')->nullable()->after('fasilitas_kmandi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kosts', function (Blueprint $table) {
            $table->dropColumn([
                'ukuran_kamar',
                'listrik_status',
                'fasilitas',
                'fasilitas_kmandi',
                'fasilitas_umum',
            ]);
        });
    }
};
