<?php

namespace App\Models;

use App\Traits\Bilingual;
use Illuminate\Database\Eloquent\Model;

class CarSubType extends Model
{
    use Bilingual;

    protected $fillable = ['car_type_id', 'name', 'name_ar'];

    public function carType()
    {
        return $this->belongsTo(CarType::class);
    }
}
