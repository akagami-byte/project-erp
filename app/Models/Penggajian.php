<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    use HasFactory;

    protected $table = 'penggajian';

    protected $fillable = [
        'user_id',
        'nomor_rekening',
        'bank',
        'nominal_gaji',
        'status_penggajian',
        'periode',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
