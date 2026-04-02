<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'qty',
        'price'
    ];

    protected $casts = [
        'qty' => 'integer',
        'price' => 'decimal:2'
    ];

    // Relasi ke Purchase
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    // Relasi ke Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Calculate subtotal
    public function getSubtotalAttribute(): float
    {
        return $this->qty * $this->price;
    }
}
