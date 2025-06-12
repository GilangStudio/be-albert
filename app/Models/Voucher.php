<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_vouchers')
                    ->withPivot('is_redeemed')
                    ->withTimestamps();
    }

    public function voucherHistories()
    {
        return $this->hasMany(VoucherHistory::class);
    }

    //scope voucher that not expired
    public function scopeNotExpired($query)
    {
        return $query->where('expiry_date', NULL)->orWhere('expiry_date', '>=', date('Y-m-d'));
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', 1);
    }

    public function scopePrivate($query)
    {
        return $query->where('is_public', 0);
    }
}
