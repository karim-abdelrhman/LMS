<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'file',
        'name',
        'mime_type',
        'size',
        'type',
    ];

    public function attachable()
    {
        return $this->morphTo();
    }
}
