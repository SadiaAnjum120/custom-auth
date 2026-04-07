<?php

namespace App\Models;
use App\Models\Product;

use App\Traits\CommonScopes;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = [
         'user_id',
        'category_id',
        'name',
        'slug',
        'is_active',
    ];
  use CommonScopes;





    public function category()
    {
        return $this->belongsTo(Category::class);
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
