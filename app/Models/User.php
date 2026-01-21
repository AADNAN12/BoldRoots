<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'postal_code',
        'is_active',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login' => 'datetime',
    ];

    /**
     * The attributes that should be set by default.
     *
     * @var array<string, string>
     */
    protected $attributes = [
        'is_active' => true,
    ];
    // ===================================
    // RELATIONS - COMMANDES
    // ===================================

    /**
     * Get all orders for the user
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get order items through orders
     */
    public function orderItems(): HasManyThrough
    {
        return $this->hasManyThrough(OrderItem::class, Order::class);
    }

    // ===================================
    // RELATIONS - PANIER & WISHLIST
    // ===================================

    /**
     * Get the user's cart
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Get the user's cart items
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }


    // ===================================
    // RELATIONS - COUPONS
    // ===================================
    /**
     * Get coupons used by this user
     * Uses coupon_usage table
     */
    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, 'coupon_usage', 'user_id', 'coupon_id')
            ->withPivot('id', 'order_id', 'discount_amount', 'created_at');
    }

    /**
     * Get coupon usages by this user
     */
    public function couponUsages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    // ===================================
    // RELATIONS - PROMOTIONS
    // ===================================

    /**
     * Get promotion usages by this user
     */
    public function promotionUsages(): HasMany
    {
        return $this->hasMany(PromotionUsage::class);
    }


    /**
     * Vérifie si l'utilisateur a un profil client associé
     */
    public function isClient(): bool
    {
        return $this->hasRole('Client');
    }

    /**
     * Vérifie si l'utilisateur est un admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(['Super Admin', 'Admin']);
    }

    /**
     * Get total amount spent by user
     */
    public function getTotalSpentAttribute(): float
    {
        return $this->orders()
            ->where('status', 'completed')
            ->sum('total_amount');
    }

    /**
     * Get total orders count
     */
    public function getTotalOrdersAttribute(): int
    {
        return $this->orders()->count();
    }

    /**
     * Check if user has used a specific coupon
     */
    public function hasUsedCoupon(int $couponId): bool
    {
        return $this->couponUsages()
            ->where('coupon_id', $couponId)
            ->exists();
    }

    /**
     * Get count of times user used a specific coupon
     */
    public function getCouponUsageCount(int $couponId): int
    {
        return $this->couponUsages()
            ->where('coupon_id', $couponId)
            ->count();
    }

    /**
     * Check if user can use a promotion
     */
    public function canUsePromotion(int $promotionId, int $maxPerCustomer = null): bool
    {
        if (!$maxPerCustomer) {
            return true;
        }

        $usageCount = $this->promotionUsages()
            ->where('promotion_id', $promotionId)
            ->count();

        return $usageCount < $maxPerCustomer;
    }

    /**
     * Get user's active cart total
     */
    public function getCartTotalAttribute(): float
    {
        return $this->cartItems()
            ->with('product')
            ->get()
            ->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });
    }

    /**
     * Get user's cart items count
     */
    public function getCartItemsCountAttribute(): int
    {
        return $this->cartItems()->sum('quantity');
    }

    /**
     * Get user's wishlist count
     */
    public function getWishlistCountAttribute(): int
    {
        return $this->wishlistItems()->count();
    }
}

