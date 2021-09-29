<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    public function getVideoAttribute($value)
    {
        if(!empty($value)){
            return asset($value);
        }
    }

    public function image()
    {
        return $this->hasOne(ProductImage::class, 'product_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

}
