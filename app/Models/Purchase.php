<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    protected $fillable = [
        'branch',
        'document_date',
        'required_date',
        'supplier_id',
        'payment_method',
        'total'
    ];

    protected $casts = [
        'document_date' => 'date',
        'required_date' => 'date',
        'total' => 'decimal:2'
    ];

    // Relasi ke Supplier
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi ke PurchaseItems
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    // Relasi ke GoodsReceipt
    public function goodsReceipts(): HasMany
    {
        return $this->hasMany(GoodsReceipt::class);
    }

    // Relasi ke Invoice
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // Calculate total from items
    public function calculateTotal(): float
    {
        return $this->items->sum(function ($item) {
            return $item->qty * $item->price;
        });
    }
}
