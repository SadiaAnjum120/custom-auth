<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CommonScopes;
class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
          'user_id',
           'is_active',
    ];
      use CommonScopes;
    public function orders()
{
    return $this->hasMany(Order::class);
}
public function user()
{
    return $this->belongsTo(User::class);
}
}
