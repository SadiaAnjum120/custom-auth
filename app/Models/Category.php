<?php

namespace App\Models;
use App\Models\Product;

use App\Traits\CommonScopes;

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
     use CommonScopes;


    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
    public function products()
{
    return $this->hasMany(Product::class);
}
public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}
public function user()
{
    return $this->belongsTo(User::class);
}
}
