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
        Schema::create('collection_genders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('collections')->onDelete('cascade'); // Menyambungkan dengan tabel collections
            $table->enum('gender', ['women', 'men', ''])->nullable(); // Gender untuk koleksi (Women, Men, or Both)
            $table->string('image'); // Gambar untuk gender tersebut
            $table->integer('display_order')->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_genders');
    }
};
