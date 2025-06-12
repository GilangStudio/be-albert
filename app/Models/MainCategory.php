<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function product_categories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, ProductCategory::class, 'main_category_id', 'product_category_id');
    }
}
