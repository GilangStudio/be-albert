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
        Schema::create('collection_banner_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_banner_id')->constrained('collection_banners')->onDelete('cascade');
            $table->string('image'); // Path gambar banner
            $table->integer('display_order')->nullable(); // Urutan tampilan gambar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_banner_images');
    }
};
