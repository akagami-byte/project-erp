<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'currency'
    ];

    // Relasi ke Purchase
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    // Relasi ke SimplePurchase
    public function simplePurchases(): HasMany
    {
        return $this->hasMany(SimplePurchase::class);
    }
}
