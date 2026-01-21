<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'is_active',
        'scope',
        'max_per_customer',
        'stop_when_stock_below',
        'min_cart_value',
        'exclude_new_products',
        'total_usage_count',
        'usage_limit',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_cart_value' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'exclude_new_products' => 'boolean',
        'total_usage_count' => 'integer',
        'usage_limit' => 'integer',
        'max_per_customer' => 'integer',
        'stop_when_stock_below' => 'integer',
    ];

    /**
     * Get products associated with this promotion
     * Uses promotion_products pivot table
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'promotion_products', 'promotion_id', 'product_id');
    }

    /**
     * Get categories associated with this promotion
     * Uses promotion_categories pivot table
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'promotion_categories', 'promotion_id', 'category_id');
    }

    public function buyXGetYRule(): HasOne
    {
        return $this->hasOne(BuyXGetYRule::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(PromotionUsage::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isActive(): bool
    {
        return $this->is_active 
            && now()->between($this->start_date, $this->end_date)
            && ($this->usage_limit === null || $this->total_usage_count < $this->usage_limit);
    }
}
