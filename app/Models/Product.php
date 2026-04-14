<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'stock',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer'
    ];

    // Relasi ke PurchaseItem
    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    // Relasi ke SaleItem
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    // Relasi ke PurchaseItem untuk purchasing
    public function purchases(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    // Relasi ke SaleItem untuk sales
    public function sales(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    // Relasi ke SimplePurchaseItem
    public function simplePurchaseItems(): HasMany
    {
        return $this->hasMany(SimplePurchaseItem::class);
    }
}
