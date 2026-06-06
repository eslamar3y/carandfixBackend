<?php

namespace App\Models;

use App\Traits\Bilingual;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use Bilingual;

    protected $fillable = ['name', 'name_ar', 'image', 'service_category_id', 'price'];

    protected static function booted()
    {
        static::saving(function ($model) {
            if ((float) $model->price == 0) $model->price = null;
        });
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function serviceCategories()
    {
        return $this->hasMany(ServiceCategory::class);
    }
}
