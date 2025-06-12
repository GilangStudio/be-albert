<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    // use HasFactory, SoftDeletes;
    use HasFactory;

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::addGlobalScope('is_active', function (Builder $builder) {
            $builder->where('is_active', 1);
        });
    }

    // public function scopeActive($query)
    // {
    //     return $query->where('is_active', 1);
    // }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function first_image()
    {
        return $this->hasOne(ProductImage::class)->where('display_order', 1);
    }

    // Relasi ke keranjang
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function product_category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

}
