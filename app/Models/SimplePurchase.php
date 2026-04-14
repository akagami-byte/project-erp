<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SimplePurchase extends Model
{
    // Konstanta Status
    public const PAYMENT_STATUS = [
        'UNPAID' => 'UNPAID',
        'PAID' => 'PAID'
    ];

    public const RECEIPT_STATUS = [
        'NOT_RECEIVED' => 'NOT_RECEIVED',
        'PARTIAL' => 'PARTIAL',
        'RECEIVED' => 'RECEIVED'
    ];

    protected $fillable = [
        'supplier_id',
        'date',
        'payment_method',
        'payment_status',
        'receipt_status',
        'total_price',
        'midtrans_order_id'
    ];

    protected $casts = [
        'date' => 'date',
        'total_price' => 'decimal:2'
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SimplePurchaseItem::class);
    }

    public function calculateTotal(): float
    {
        return $this->items->sum(function ($item) {
            return $item->qty_order * $item->price;
        });
    }
}

