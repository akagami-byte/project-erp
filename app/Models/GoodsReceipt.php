<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoodsReceipt extends Model
{
    protected $fillable = [
        'purchase_id',
        'location',
        'receipt_date'
    ];

    protected $casts = [
        'receipt_date' => 'date'
    ];

    // Relasi ke Purchase
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    // Relasi ke GoodsReceiptItems
    public function items(): HasMany
    {
        return $this->hasMany(GoodsReceiptItem::class);
    }
}

