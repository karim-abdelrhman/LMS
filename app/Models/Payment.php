<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class Payment extends Model
{
    protected $fillable = [
        'legal_case_id',
        'client_id',
        'amount',
        'type',
        'status',
        'method',
        'proof_image',
        'due_date',
        'paid_at',
        'verified_by',
        'verified_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'type' => PaymentType::class,
            'status' => PaymentStatus::class,
            'method' => PaymentMethod::class,
            'due_date' => 'date',
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Payment $payment): void {
            if ($payment->method === PaymentMethod::Cash) {
                $payment->proof_image = null;

                return;
            }

            if ($payment->method !== null && $payment->method !== PaymentMethod::Cash) {
                if (blank($payment->proof_image)) {
                    throw ValidationException::withMessages([
                        'proof_image' => 'صورة الإثبات مطلوبة لهذه الطريقة.',
                    ]);
                }
            }
        });
    }

    public function legalCase(): BelongsTo
    {
        return $this->belongsTo(LegalCase::class, 'legal_case_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
