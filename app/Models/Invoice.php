<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'purchase_id',
        'invoice_date',
        'total_amount',
        'status',
        'payment_date',
        'notes'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'total_amount' => 'decimal:2',
        'payment_date' => 'date',
        'status' => 'string'
    ];

    // Relasi ke Purchase
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    // Check if invoice is paid
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    // Mark as paid
    public function markAsPaid(string $notes = null): void
    {
        $this->status = 'paid';
        $this->payment_date = now();
        if ($notes) {
            $this->notes = $notes;
        }
        $this->save();
    }
}

