<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SimplePurchaseItem extends Model
{
    protected $fillable = [
        'simple_purchase_id',
        'product_id',
        'qty_order',
        'qty_received',
        'price',
        'subtotal'
    ];

    protected $casts = [
        'qty_order' => 'integer',
        'qty_received' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function simplePurchase(): BelongsTo
    {
        return $this->belongsTo(SimplePurchase::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->qty_order * $this->price;
    }
}

