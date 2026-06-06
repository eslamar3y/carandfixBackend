<?php

namespace App\Models;

use App\Traits\Bilingual;
use Illuminate\Database\Eloquent\Model;

class AboutAndContact extends Model
{
    use Bilingual;

    protected $table = 'about_and_contact';
    protected $fillable = ['description_en', 'description_ar', 'email', 'phone'];
}
