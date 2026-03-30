<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseCategory extends Model
{
    public function legalCases()
    {
        return $this->hasMany(LegalCase::class, 'category_id');
    }
}
