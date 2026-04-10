<?php

namespace App\Enums;

enum PaymentType: string
{
    case FilingFee = 'filing_fee';
    case SuccessFee = 'success_fee';

    public function label(): string
    {
        return match ($this) {
            self::FilingFee => 'رسوم تسجيل',
            self::SuccessFee => 'رسوم نجاح',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::FilingFee => 'info',
            self::SuccessFee => 'success',
        };
    }
}
