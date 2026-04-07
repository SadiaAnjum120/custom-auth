<?php

namespace App\Models;
use App\Traits\CommonScopes;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  protected $fillable = [
    'user_id',
    'customer_id',
    'order_number',
    'order_date',
    'order_status',
    'payment_status',
    'sub_total',
    'tax',
    'discount',
    'total_amount',
    'paid_amount',
    'due_amount',
    'notes',
];
    use CommonScopes;
    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }

    // ============================
    // RELATIONSHIPS
    // ============================

    // Order belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Order belongs to Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Order has many Order Items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


}
