<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourtCategory extends Model
{
    protected $fillable = ['name'];
    public function courts()
    {
        return $this->hasMany(Court::class, 'category_id');
    }
}
