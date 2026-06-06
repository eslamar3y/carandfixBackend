<?php

namespace App\Models;

use App\Traits\Bilingual;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use Bilingual;

    protected $fillable = ['service_id', 'name', 'name_ar', 'image', 'price', 'fields'];

    protected $attributes = [
        'fields' => '[]',
    ];

    protected function casts(): array
    {
        return [
            'fields' => 'array',
            'price' => 'decimal:2',
        ];
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (isset($model->fields) && is_array($model->fields)) {
                $model->fields = array_map(fn($v) => $v ? 1 : 0, $model->fields);
            }
            if ((float) $model->price == 0) $model->price = null;
        });
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
