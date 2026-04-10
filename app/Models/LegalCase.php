<?php

namespace App\Models;

use App\Enums\CaseStatus;
use Illuminate\Database\Eloquent\Model;

class LegalCase extends Model
{
    protected $fillable = [
        'title',
        'case_number',
        'description',
        'category_id',
        'court_id',
        'status',
    ];

    protected $table = 'cases';

    protected $casts = [
        'status' => CaseStatus::class,
    ];

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'case_client', 'case_id', 'client_id')
            ->using(CaseClient::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'legal_case_id');
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

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
