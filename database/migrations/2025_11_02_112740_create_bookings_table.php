<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // pencari kost
            $table->foreignId('kost_id')->constrained()->onDelete('cascade');
            $table->date('tanggal_mulai');
            $table->integer('lama_sewa')->default(1); // bulan
            $table->integer('harga_per_bulan');
            $table->integer('pajak')->default(20000);
            $table->integer('total');
            $table->string('status', 50)->default('menunggu_pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
