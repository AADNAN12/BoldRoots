<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionUsage extends Model
{
    use HasFactory;

    protected $table = 'promotion_usage';

    public $timestamps = false;

    protected $fillable = [
        'promotion_id',
        'user_id',
        'order_id',
        'discount_amount',
        'quantity_purchased',
        'created_at',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'quantity_purchased' => 'integer',
        'created_at' => 'datetime',
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
