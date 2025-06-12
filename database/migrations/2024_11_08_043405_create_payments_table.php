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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->nullable();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Relasi ke tabel orders
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
            $table->string('external_id')->nullable();
            $table->string('invoice_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_channel')->nullable();
            $table->decimal('amount', 15, 0);
            $table->string('invoice_url')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->default('PENDING');
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->text('callback_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
