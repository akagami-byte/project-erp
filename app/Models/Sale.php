<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'branch',
        'sales_date',
        'customer_name',
        'total'
    ];

    protected $casts = [
        'sales_date' => 'date',
        'total' => 'decimal:2'
    ];

    // Relasi ke SaleItems
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    // Calculate total from items
    public function calculateTotal(): float
    {
        return $this->items->sum(function ($item) {
            return $item->qty * $item->price;
        });
    }
}
