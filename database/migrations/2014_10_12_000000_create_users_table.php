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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');                     // Nama user
            $table->string('country_code')->nullable();
            $table->string('phone_number')->unique();    // Nomor telepon
            $table->date('birth_date')->nullable();      // Tanggal lahir
            $table->enum('gender', ['MALE', 'FEMALE']);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('points')->default(0);       // Poin reward user
            $table->boolean('is_admin')->default(false); // Menandakan apakah user pengelola toko atau pembeli
            $table->rememberToken();
            $table->string('reset_password_token')->nullable();
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
