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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique()->nullable();      // SKU (Stock Keeping Unit)
            $table->string('product_code')->unique()->nullable();         // Kode unik untuk tiap product
            $table->string('name');                         // Nama product
            $table->string('color');                         // Warna product
            $table->decimal('price', 15, 0);                // Harga product
            $table->integer('discount_percentage')->nullable(); // Diskon dalam persen, opsional
            $table->text('description')->nullable();        // Deskripsi product
            $table->string('size_guide')->nullable();         // Panduan ukuran

            $table->boolean('is_preorder')->default(false);
            $table->integer('preorder_days')->nullable();

            $table->foreignId('product_category_id')->nullable()->constrained('product_categories')->onDelete('set null');

            $table->boolean('is_active')->default(true);    // Status product aktif atau tidak

            $table->string('slug')->unique();
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
