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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Collection
            $table->text('description')->nullable(); // Deskripsi koleksi
            $table->enum('type', ['regular', 'bridal']);
            $table->boolean('is_active')->default(true);
            $table->year('collection_year')->nullable();
            $table->string('main_image');
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
