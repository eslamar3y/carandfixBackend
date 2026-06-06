<?php

namespace App\Models;

use App\Traits\Bilingual;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use Bilingual;

    protected $fillable = ['name', 'name_ar', 'image', 'price', 'brand_category_id'];

    protected static function booted()
    {
        static::saving(function ($model) {
            if ((float) $model->price == 0) $model->price = null;
        });
    }

    public function brandCategory()
    {
        return $this->belongsTo(BrandCategory::class);
    }

    public function brandCategories()
    {
        return $this->morphMany(BrandCategory::class, 'categorizable');
    }
}
