<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionBannerImage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function collection_banner()
    {
        return $this->belongsTo(CollectionBanner::class);
    }
}