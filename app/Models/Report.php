<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'car_id', 'technician_id', 'serial', 'final_decision', 'current_mileage',
        'report_date', 'car_options',
        'chassis_percent', 'exterior_percent', 'road_test_percent',
        'power_train_percent', 'electrical_percent', 'braking_percent',
        'suspension_percent', 'ac_cooling_percent',
        'exterior', 'chassis_frame', 'road_test', 'power_train',
        'electrical_system', 'braking_safety', 'suspension', 'ac_cooling',
        'all_notes', 'inspection_systems', 'note_images',
    ];

    protected function casts(): array
    {
        return [
            'exterior' => 'array',
            'chassis_frame' => 'array',
            'road_test' => 'array',
            'power_train' => 'array',
            'electrical_system' => 'array',
            'braking_safety' => 'array',
            'suspension' => 'array',
            'ac_cooling' => 'array',
            'all_notes' => 'array',
            'inspection_systems' => 'array',
            'note_images' => 'array',
        ];
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
