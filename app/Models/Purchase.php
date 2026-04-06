<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_name',
        'purchase_date',
        'value',
        'category',
        'payment_method',
        'seller'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'value' => 'decimal:2'
    ];
}