<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    public function category()
    {
        return $this->belongsTo(CourtCategory::class, 'category_id');
    }

    public function legalCases()
    {
        return $this->hasMany(LegalCase::class, 'court_id');
    }
}
