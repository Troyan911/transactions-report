<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'type',
        'debit',
        'credit',
        'cash_operation',
    ];

    public function transactions(): HasMany
    {
        return $this->HasMany(Transaction::class);
    }
}
