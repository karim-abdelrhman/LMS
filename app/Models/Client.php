<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'national_id',
        'photo',
        'id_front_photo',
        'id_back_photo',
    ];
    public function legalCases()
    {
        return $this->belongsToMany(LegalCase::class, 'case_client', 'client_id', 'case_id');
    }
}
