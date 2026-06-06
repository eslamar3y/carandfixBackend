<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    const TYPES = ['technical' => 'Technical', 'billing' => 'Billing', 'app' => 'App Issue', 'other' => 'Other'];

    protected $fillable = ['user_id', 'issue_type', 'description', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
