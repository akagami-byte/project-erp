<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class JournalEntry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'journal_date',
        'reference_code',
        'transaction_type',
        'description',
        'branch_id',
        'created_by',
    ];

    protected $casts = [
        'journal_date' => 'date',
        'branch_id' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(JournalEntryDetail::class);
    }

    public function getTotalDebitAttribute()
    {
        return $this->details()->sum('debit');
    }

    public function getTotalCreditAttribute()
    {
        return $this->details()->sum('credit');
    }

    public function getIsBalancedAttribute()
    {
        return $this->total_debit == $this->total_credit;
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('journal_date', [$startDate, $endDate]);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    public function scopeByReference($query, $search)
    {
        return $query->where('reference_code', 'like', "%{$search}%")
                     ->orWhere('description', 'like', "%{$search}%");
    }

    public function scopeWithDetails($query)
    {
        return $query->with('details.account');
    }
}
?>

