<?php

namespace App\Models;

use App\Traits\Bilingual;
use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    use Bilingual;

    protected $fillable = ['name', 'name_ar', 'image'];

    public function carSubTypes()
    {
        return $this->hasMany(CarSubType::class);
    }
}
