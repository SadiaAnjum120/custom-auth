<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    protected $fillable = [
         'user_id',
        'name',
        'sku',
        'category_id',
        'sub_category_id',
        'price',
        'cost',
        'quantity',
        'image',
          'is_active',


    ];
     // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    // Accessor for image URL
public function getImageAttribute($value)
{
    return $value ? asset('storage/' . $value) : null;
}



}
