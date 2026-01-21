<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'delivery_number',
        'delivery_date',
        'carrier_name',
        'tracking_number',
        'status',
        'delivered_at',
        'recipient_name',
        'signature_image',
        'pdf_path',
        'notes',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'delivered_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
