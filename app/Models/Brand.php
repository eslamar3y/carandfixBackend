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
            if (is_array($model->fields)) {
                $model->fields = array_map(fn($v) => $v ? 1 : 0, $model->fields);
            }
        });
    }

    public function brandCategory()
    {
        return $this->belongsTo(BrandCategory::class);
    }
}
