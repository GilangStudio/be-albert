<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
            $table->string('recipient_name');             // Nama penerima
            $table->string('phone_number');               // Nomor telepon penerima
            $table->string('detail_address');             // Baris alamat utama
            $table->integer('postal_code');                // Kode pos
            $table->integer('subdistrict_id');               // Kelurahan
            $table->integer('district_id');                   // Kecamatan
            $table->integer('city_id');                       // Kota
            $table->integer('province_id');                   // Provinsi
            $table->string('country');                    // Negara
            $table->boolean('is_primary')->default(false); // Menandakan alamat utama
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
