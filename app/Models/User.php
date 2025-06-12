<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'country_code',
        'phone_number',
        'birth_date',
        'email',
        'points',
        'is_admin',
        'password',
        'reset_password_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke alamat
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // Relasi ke keranjang
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    // Relasi ke pesanan
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function ordersActive()
    {
        return $this->hasMany(Order::class)->whereNotIn('status', ['CANCELED', 'DELIVERED']);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // Relasi ke voucher yang digunakan
    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'user_vouchers')
                    ->withPivot('is_redeemed')
                    ->withTimestamps();
    }

    public function scopeMainDetail($query) 
    {
        return $query->select('id', 'name', 'email', 'country_code', 'phone_number');
    }

    public function scopePoints($query)
    {
        //get points sum from orders.order_products
        return OrderProduct::whereHas('order', function ($query) {
            $query->where('user_id', $this->id);
        })->where('status', 'DELIVERED')->sum('reward_points');
    }
}
