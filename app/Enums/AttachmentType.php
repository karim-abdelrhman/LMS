<?php

namespace App\Enums;

enum AttachmentType : string
{
    case CONTRACT = 'contract';
    case ID_CARD = 'id_card';
    case RECEIPT = 'receipt';
    case EVIDENCE = 'evidence';
    case COURT_DOCUMENT = 'court_document';

    // Label عربي يظهر في UI
    public function label(): string
    {
        return match ($this) {
            self::CONTRACT => 'عقد',
            self::ID_CARD => 'بطاقة',
            self::RECEIPT => 'إيصال',
            self::EVIDENCE => 'مستند إثبات',
            self::COURT_DOCUMENT => 'مستند محكمة',
        };
    }

    // عشان تستخدمها في Select بسهولة
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [
                $case->value => $case->label()
            ])
            ->toArray();
    }

    public function color(): string
    {
        return match ($this) {
            self::CONTRACT => 'success',
            self::ID_CARD => 'info',
            self::RECEIPT => 'warning',
            self::EVIDENCE => 'danger',
            self::COURT_DOCUMENT => 'primary',
        };
    }

    // Icon لو عايز تظهر أيقونات مع نوع المستند
    public function icon(): string
    {
        return match ($this) {
            self::CONTRACT => 'heroicon-o-document-text',
            self::ID_CARD => 'heroicon-o-identification',
            self::RECEIPT => 'heroicon-o-receipt-percent',
            self::EVIDENCE => 'heroicon-o-shield-check',
            self::COURT_DOCUMENT => 'heroicon-o-scale',
        };
    }
}
