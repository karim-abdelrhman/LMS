<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    public function legalCase()
    {
        return $this->belongsTo(LegalCase::class, 'case_id');
    }
}
