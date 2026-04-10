<?php

namespace App\Models;

use App\Enums\CaseStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CaseClient extends Pivot
{
    protected $table = 'case_client';

    public $incrementing = true;

    /**
     * When a client is linked to a case, ensure expected payments exist (idempotent).
     */
    protected static function booted(): void
    {
        static::created(function (CaseClient $pivot): void {
            Payment::query()->firstOrCreate(
                [
                    'legal_case_id' => $pivot->case_id,
                    'client_id' => $pivot->client_id,
                    'type' => PaymentType::FilingFee,
                ],
                [
                    'amount' => 250,
                    'status' => PaymentStatus::Pending,
                ],
            );

            $case = LegalCase::query()->find($pivot->case_id);
            if ($case && $case->status === CaseStatus::WON) {
                Payment::query()->firstOrCreate(
                    [
                        'legal_case_id' => $pivot->case_id,
                        'client_id' => $pivot->client_id,
                        'type' => PaymentType::SuccessFee,
                    ],
                    [
                        'amount' => 1000,
                        'status' => PaymentStatus::Pending,
                    ],
                );
            }
        });
    }
}
