<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasFactory, Notifiable, MustVerifyEmail;

    // =============================
    // ✅ ROLE CONSTANTS
    // =============================
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_SHOP_ADMIN  = 'shop_admin';
    public const ROLE_CUSTOMER    = 'customer';

    // =============================
    // ✅ ROLE CHECK FUNCTIONS
    // =============================
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isShopAdmin(): bool
    {
        return $this->role === self::ROLE_SHOP_ADMIN;
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin; // full admin flag
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'email',
        'image_url',
        'phone_number',
        'is_active',
        'password',
        'remember_token',
         'is_admin',
         'role',
    'shop_name',
    'shop_url',
    'shop_number',
    'approval_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Full name (first_name + last_name).
     */
    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
    public function orders()
{
    return $this->hasMany(Order::class);
}
public function categories()
{
    return $this->hasMany(Category::class);
}

public function subCategories()
{
    return $this->hasMany(SubCategory::class);
}

public function products()
{
    return $this->hasMany(Product::class);
}

public function customers()
{
    return $this->hasMany(Customer::class);
}
}
