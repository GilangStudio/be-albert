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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Relasi ke tabel products
            $table->foreignId('size_id')->nullable()->constrained('product_sizes')->onDelete('set null'); // Ukuran yang dipilih
            $table->integer('quantity');                 // Jumlah product dalam keranjang
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
