<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalCase extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'court_id',
    ];
    protected $table = 'cases';
    public function clients()
    {
        return $this->belongsToMany(Client::class, 'case_client', 'case_id', 'client_id');
    }

    public function category()
    {
        return $this->belongsTo(CaseCategory::class, 'category_id');
    }

    public function court()
    {
        return $this->belongsTo(Court::class, 'court_id');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class, 'case_id');
    }
}
