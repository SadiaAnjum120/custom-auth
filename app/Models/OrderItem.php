<?php

namespace App\Models;
use App\Traits\CommonScopes;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'category_id',
        'sub_category_id',
        'product_id',
        'quantity'
    ];
    use CommonScopes;
     public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }
    // Relationships

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
