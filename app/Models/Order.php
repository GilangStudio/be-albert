<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke alamat
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    // Relasi ke voucher
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    // Relasi ke item pesanan
    public function order_products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeTotalProducts($query)
    {
        return $query->withCount('order_products as total_products');
    }
}
