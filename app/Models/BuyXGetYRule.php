<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuyXGetYRule extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'promotion_id',
        'buy_quantity',
        'get_quantity',
    ];

    protected $casts = [
        'buy_quantity' => 'integer',
        'get_quantity' => 'integer',
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }
}
