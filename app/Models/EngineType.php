<?php

namespace App\Models;

use App\Traits\Bilingual;
use Illuminate\Database\Eloquent\Model;

class EngineType extends Model
{
    use Bilingual;

    protected $fillable = ['name', 'name_ar'];
}
