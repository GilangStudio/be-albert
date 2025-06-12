<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderProduct extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke pesanan
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(ProductSize::class);
    }

    public function scopeProductDetail($query)
    {
        return $query->with(['product' => function ($query) {
            $query->with('first_image:id,product_id,image')->select('id', 'name', 'price');
        }, 'size' => function ($query) {
            $query->select('id', 'size');
        }]);
    }
}
