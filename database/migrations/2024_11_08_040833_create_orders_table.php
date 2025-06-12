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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 15, 0);
            $table->decimal('shipping_cost', 15, 0);
            $table->decimal('discount', 15, 0);
            $table->decimal('total', 15, 0);

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->onDelete('set null'); // Voucher yang digunakan
            $table->foreignId('address_id')->nullable()->constrained('addresses')->onDelete('set null'); // Alamat pengiriman
            $table->enum('shipping_type', ['PICKUP', 'INSTANT', 'THIRD_PARTY']); // Tipe pengiriman
            $table->string('shipping_provider')->nullable(); // Contoh: JNE, J&T (untuk THIRD_PARTY)

            // $table->enum('status', ['PENDING', 'PAID', 'SHIPPED', 'DELIVERED', 'CANCELED']); // Status order
            $table->enum('status', ['PENDING', 'PAID', 'PARTIALLY_CANCELED', 'CANCELED', 'SHIPPING', 'DELIVERED'])->default('PENDING');
            $table->string('tracking_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
