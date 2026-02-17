<?php

namespace App\Models;
use App\Models\Product;


use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;

class Category extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'is_active',
    ];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
    public function products()
{
    return $this->hasMany(Product::class);
}
}
