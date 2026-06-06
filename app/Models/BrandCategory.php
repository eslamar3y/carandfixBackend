<?php

namespace App\Models;

use App\Traits\Bilingual;
use Illuminate\Database\Eloquent\Model;

class BrandCategory extends Model
{
    use Bilingual;

    protected $fillable = ['name', 'name_ar', 'image', 'categorizable_type', 'categorizable_id'];

    public function categorizable()
    {
        return $this->morphTo();
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'categorizable_id');
    }
}
