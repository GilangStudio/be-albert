<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionBanner extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke images
    public function images()
    {
        return $this->hasMany(CollectionBannerImage::class)->orderBy('display_order', 'asc');
    }

    // Scope untuk banner collection
    public function scopeCollection($query)
    {
        return $query->where('type', 'collection');
    }

    // Scope untuk banner bridal
    public function scopeBridal($query)
    {
        return $query->where('type', 'bridal');
    }
}