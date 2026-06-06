<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'title', 'body', 'title_ar', 'body_ar', 'is_order', 'order_id', 'date', 'admin_sent',
    ];

    protected function casts(): array
    {
        return [
            'is_order' => 'boolean',
            'admin_sent' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
