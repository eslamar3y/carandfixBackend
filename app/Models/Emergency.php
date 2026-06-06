<?php

namespace App\Models;

use App\Traits\Bilingual;
use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    use Bilingual;

    protected $fillable = ['name', 'name_ar', 'image', 'price', 'fields', 'service_category_id'];

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
            if ((float) $model->price == 0) $model->price = null;
        });
    }

    public function brandCategories()
    {
        return $this->morphMany(BrandCategory::class, 'categorizable');
    }

    public function parent()
    {
        return $this->belongsTo(Emergency::class, 'service_category_id');
    }

    public function serviceCategories()
    {
        return $this->hasMany(Emergency::class, 'service_category_id');
    }
}
