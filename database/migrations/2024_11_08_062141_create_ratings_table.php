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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Relasi ke tabel products
            // $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Relasi ke tabel orders
            $table->foreignId('order_product_id')->constrained('order_products')->onDelete('cascade'); // Relasi ke tabel orders
            $table->tinyInteger('rating')->unsigned()->comment('Rating dari 1 sampai 5'); // Rating (1-5)
            $table->text('review')->nullable();            // Review opsional
            $table->timestamps();

            // $table->unique(['user_id', 'product_id', 'order_id']); // User hanya bisa memberi rating sekali per product dalam 1 order
            $table->unique(['order_product_id']); // User hanya bisa memberi rating sekali per product dalam 1 order
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
