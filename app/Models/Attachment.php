<?php

namespace App\Models;

use App\Enums\AttachmentType;
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

    protected $casts = [
        'type' => AttachmentType::class,
    ];

    public function attachable()
    {
        return $this->morphTo();
    }
}
