<?php

namespace App\Enums;

enum CaseStatus: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
    case WON = 'won';

    // Label عربي يظهر في UI
    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'مفتوحة',
            self::CLOSED => 'مقفوله',
            self::WON => 'مكتسبة',
        };
    }
}
