<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'value',
        'color_code',
    ];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function colorVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'color_id');
    }

    public function sizeVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'size_id');
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'color_id');
    }
}
