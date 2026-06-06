<?php

namespace App\Models;

use App\Traits\Bilingual;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use Bilingual;

    protected $fillable = ['brand_category_id', 'name', 'name_ar', 'image', 'price', 'fields'];

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

    public function brandCategory()
    {
        return $this->belongsTo(BrandCategory::class);
    }
}
