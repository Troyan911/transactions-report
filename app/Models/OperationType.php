<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'code',
        'name',
        'description',
        'debit',
        'credit',
    ];
}
