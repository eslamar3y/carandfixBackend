<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'customer_name', 'payment_method', 'generated_at',
        'items', 'gross_amount', 'discount_amount', 'net_amount',
    ];

    protected function casts(): array
    {
        return [
            'generated_at' => 'date',
            'items' => 'array',
            'gross_amount' => 'string',
            'discount_amount' => 'string',
            'net_amount' => 'string',
        ];
    }

    protected static function booted()
    {
    }
}
