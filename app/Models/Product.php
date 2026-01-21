<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'compare_price',
        'is_new',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'is_new' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the homepage image for the product (for featured products display)
     */
    public function homepageImage()
    {
        return $this->images()->where('is_homepage_image', true)->first() 
            ?? $this->images()->where('is_primary', true)->first() 
            ?? $this->images()->orderBy('sort_order')->first();
    }

    /**
     * Get the primary image for the product
     */
    public function primaryImage()
    {
        return $this->images()->where('is_primary', true)->first() 
            ?? $this->images()->orderBy('sort_order')->first();
    }

    /**
     * Get promotions associated with this product
     * Uses promotion_products pivot table
     */
    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class, 'promotion_products', 'product_id', 'promotion_id');
    }

    /**
     * Get coupons associated with this product
     * Uses coupon_products pivot table
     */
    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, 'coupon_products', 'product_id', 'coupon_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
