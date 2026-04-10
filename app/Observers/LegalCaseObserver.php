<?php

namespace App\Observers;

use App\Enums\CaseStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\LegalCase;
use App\Models\Payment;

class LegalCaseObserver
{
    public function updated(LegalCase $legalCase): void
    {
        if (! $legalCase->wasChanged('status')) {
            return;
        }

        if ($legalCase->status !== CaseStatus::WON) {
            return;
        }

        $legalCase->loadMissing('clients');

        foreach ($legalCase->clients as $client) {
            Payment::query()->firstOrCreate(
                [
                    'legal_case_id' => $legalCase->id,
                    'client_id' => $client->id,
                    'type' => PaymentType::SuccessFee,
                ],
                [
                    'amount' => 1000,
                    'status' => PaymentStatus::Pending,
                ],
            );
        }
    }
}
