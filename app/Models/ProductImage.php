<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'color_id',
        'image_path',
        'is_primary',
        'is_homepage_image',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_homepage_image' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'color_id');
    }
}
