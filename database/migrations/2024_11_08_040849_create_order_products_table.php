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
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Relasi ke tabel orders
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // product yang dipesan
            $table->foreignId('size_id')->nullable()->constrained('product_sizes')->onDelete('set null'); // Ukuran product
            $table->integer('quantity');                 // Jumlah product yang dipesan
            $table->decimal('price', 15, 0);             // Harga per product
            $table->integer('reward_points')->default(0);   // Poin reward yang didapat dari order ini
            $table->enum('status', ['PENDING', 'PAID', 'CANCELED', 'SHIPPING', 'DELIVERED'])->default('PENDING');
            $table->boolean('is_rated')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
