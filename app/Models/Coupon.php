<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'discount_value',
        'valid_from',
        'valid_until',
        'is_active',
        'usage_limit',
        'usage_per_customer',
        'used_count',
        'min_cart_value',
        'exclude_new_products',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_cart_value' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'exclude_new_products' => 'boolean',
        'usage_limit' => 'integer',
        'usage_per_customer' => 'integer',
        'used_count' => 'integer',
    ];

    /**
     * Get products associated with this coupon
     * Uses coupon_products pivot table
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'coupon_products', 'coupon_id', 'product_id');
    }

    /**
     * Get categories associated with this coupon
     * Uses coupon_categories pivot table
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'coupon_categories', 'coupon_id', 'category_id');
    }

    /**
     * Get users who have used this coupon
     * Uses coupon_usage table
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'coupon_usage', 'coupon_id', 'user_id')
            ->withPivot('id', 'order_id', 'discount_amount', 'created_at');
    }
    
    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isValid(): bool
    {
        return $this->is_active 
            && ($this->valid_from === null || now()->gte($this->valid_from))
            && ($this->valid_until === null || now()->lte($this->valid_until))
            && ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }
}
