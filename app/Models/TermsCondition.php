<?php

namespace App\Models;

use App\Traits\Bilingual;
use Illuminate\Database\Eloquent\Model;

class TermsCondition extends Model
{
    use Bilingual;

    protected $fillable = ['name_en', 'name_ar', 'description_en', 'description_ar'];
}
