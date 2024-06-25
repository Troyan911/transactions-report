<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'timestamp',
        'type',
        'amount',
        'created_at',
        'updated_at',
    ];

    //    public function transactionType(): BelongsTo
    //    {
    //        return $this->belongsTo(TransactionType::class);
    //    }

    //    public function scopeDateFrom($query, $dateFrom)
    //    {
    //        return $query->where('timestamp', '>=', $dateFrom);
    //    }
    //
    //    public function scopeDateTo($query, $dateTo)
    //    {
    //        return $query->where('timestamp', '<=', $dateTo);
    //    }

    //    public function scopeBetweenDates($query, $start, $end)
    //    {
    //        return $query->whereBetween('timestamp', [$start, $end]);
    //    }

}
