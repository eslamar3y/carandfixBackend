<?php

namespace App\Models;

use App\Services\FCMService;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'user_id', 'vin_number', 'car_type_id', 'car_sub_type_id',
        'engine_type_id', 'status', 'color', 'registration_number',
        'year_of_production', 'engine_power', 'last_oil_change_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function carType()
    {
        return $this->belongsTo(CarType::class);
    }

    public function carSubType()
    {
        return $this->belongsTo(CarSubType::class);
    }

    public function engineType()
    {
        return $this->belongsTo(EngineType::class);
    }

    public function report()
    {
        return $this->hasOne(Report::class);
    }

    public function images()
    {
        return $this->hasMany(CarImage::class);
    }

    protected static function booted()
    {
        static::saved(function ($car) {
            if ($car->wasChanged('status') && in_array($car->status, ['approved', 'rejected'])) {
                $isApproved = $car->status === 'approved';
                $titleEn = $isApproved ? 'Car Approved' : 'Car Rejected';
                $bodyEn = $isApproved
                    ? 'Your car (' . $car->vin_number . ') has been approved'
                    : 'Your car (' . $car->vin_number . ') has been rejected';
                $titleAr = $isApproved ? 'تم قبول السيارة' : 'تم رفض السيارة';
                $bodyAr = $isApproved
                    ? 'تم قبول سيارتك (' . $car->vin_number . ')'
                    : 'تم رفض سيارتك (' . $car->vin_number . ')';

                \App\Models\Notification::create([
                    'user_id' => $car->user_id,
                    'title' => $titleEn,
                    'body' => $bodyEn,
                    'title_ar' => $titleAr,
                    'body_ar' => $bodyAr,
                    'is_order' => false,
                    'admin_sent' => false,
                    'date' => now(),
                ]);

                $car->user && app(FCMService::class)->send(
                    $car->user,
                    $titleEn,
                    $bodyEn,
                    null,
                    'car_status',
                    null,
                    $titleAr,
                    $bodyAr,
                );
            }
        });
    }
}
