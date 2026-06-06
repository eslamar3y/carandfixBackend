<?php

namespace App\Models;

use App\Traits\Bilingual;
use Illuminate\Database\Eloquent\Model;

class BatteryVoltage extends Model
{
    use Bilingual;

    protected $fillable = ['name', 'name_ar'];
}
