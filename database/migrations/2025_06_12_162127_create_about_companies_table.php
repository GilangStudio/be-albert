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
        Schema::create('about_companies', function (Blueprint $table) {
            $table->id();
            $table->string('banner_image')->nullable();
            $table->text('main_description')->nullable();
            $table->string('section_image')->nullable();
            $table->text('section_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_companies');
    }
};
