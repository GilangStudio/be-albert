<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function collection_genders() {
        return $this->hasMany(CollectionGender::class);
    }
    
    public function collection_images() {
        return $this->hasMany(CollectionGender::class);
    }
}
