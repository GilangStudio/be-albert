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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('name');                       // Nama voucher
            $table->text('description')->nullable();    // Deskripsi voucher
            $table->string('code')->unique();            // Kode voucher
            $table->integer('discount_percentage'); // Diskon dalam persen
            $table->decimal('minimum_order', 15, 0)->nullable(); // Minimal pembelian untuk menggunakan voucher
            // $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->date('expiry_date')->nullable();                 // Tanggal kadaluarsa
            $table->integer('duration')->nullable();                // Durasi voucher dalam hari
            // $table->boolean('is_active')->default(true);   // Status voucher aktif atau tidak
            $table->enum('type', ['GENERAL', 'NEW_USER'])->default('GENERAL'); // Jenis voucher
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
