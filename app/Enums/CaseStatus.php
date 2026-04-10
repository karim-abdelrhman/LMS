<?php

namespace App\Enums;

enum CaseStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case JUDGMENT_ISSUED = 'judgment_issued';
    case APPEALED = 'appealed';
    case CLOSED = 'closed';
    case WON = 'won';

    // Label عربي يظهر في UI
    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'مفتوحة',
            self::IN_PROGRESS => 'قيد التنفيذ',
            self::JUDGMENT_ISSUED => 'تم إصدار الحكم',
            self::APPEALED => 'مُستأنفة',
            self::CLOSED => 'مقفوله',
            self::WON => 'مكتسبة',
        };
    }
}
