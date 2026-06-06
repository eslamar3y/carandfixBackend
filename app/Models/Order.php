<?php

namespace App\Models;

use App\Services\FCMService;
use App\Traits\Bilingual;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Bilingual;

    protected $fillable = [
        'user_id', 'car_id', 'price', 'lat', 'long', 'phone',
        'manufactory', 'battery_voltage_id', 'with_service', 'car_license',
        'with_filter', 'start_time', 'end_time', 'pick_date', 'note',
        'payment_method', 'type', 'item_id', 'status', 'item_name',
        'item_name_ar', 'technician_id',
    ];

    protected function getItemNameAttribute($value): mixed
    {
        return $this->localize('item_name', $value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if ((float) $model->price == 0) $model->price = null;
        });

        static::saved(function ($order) {
            if ($order->wasChanged('status')) {
                $isCompleted = $order->status === 'completed';
                $bodyEn = $isCompleted ? 'your ordered has been completed successfully' : 'your ordered has been cancelled';
                $bodyAr = $isCompleted ? 'تم إكمال طلبك بنجاح' : 'تم إلغاء طلبك';

                \App\Models\Notification::create([
                    'user_id' => $order->user_id,
                    'title' => 'Order #' . $order->id,
                    'body' => $bodyEn,
                    'title_ar' => 'الطلب #' . $order->id,
                    'body_ar' => $bodyAr,
                    'is_order' => false,
                    'order_id' => $order->id,
                    'admin_sent' => false,
                    'date' => now(),
                ]);

                $order->user && app(FCMService::class)->send(
                    $order->user,
                    'Order #' . $order->id,
                    $bodyEn,
                    $order->id,
                    'order_status',
                );
            }
        });
    }
}
