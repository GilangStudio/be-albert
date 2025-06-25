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
        Schema::create('collection_banners', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['collection', 'bridal']); // Tipe banner (Collection atau Bridal)
            $table->text('description')->nullable(); // Deskripsi untuk semua banner
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_banners');
    }
};
