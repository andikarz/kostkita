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
        Schema::create('kosts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('nama');
            $table->enum('jenis', ['putra', 'putri', 'campur'])->default('campur');
            $table->text('deskripsi')->nullable();
            $table->string('alamat');
            $table->string('kecamatan')->nullable(); // Purwokerto Selatan, Timur, Barat, Utara
            $table->string('kota')->default('Purwokerto');
            $table->integer('harga_bulanan');
            $table->integer('stok_kamar')->default(0);
            $table->string('cover')->nullable();
            $table->boolean('is_recommended')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kosts');
    }
};
