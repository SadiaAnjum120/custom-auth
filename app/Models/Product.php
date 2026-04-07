<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\CommonScopes;

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
      use CommonScopes;
        public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }
     // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}
public function user()
{
    return $this->belongsTo(User::class);
}

    // Accessor for image URL
public function getImageAttribute($value)
{
    return $value
        ? asset('storage/products/' . $value)
        : null;
}




}
