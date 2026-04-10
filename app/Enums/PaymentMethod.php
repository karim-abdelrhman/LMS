<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Instapay = 'instapay';
    case VodafoneCash = 'vodafone_cash';
    case Cash = 'cash';

    public function label(): string
    {
        return match ($this) {
            self::Instapay => 'إنستاباي',
            self::VodafoneCash => 'فودافون كاش',
            self::Cash => 'نقدي',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Instapay => 'info',
            self::VodafoneCash => 'primary',
            self::Cash => 'gray',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public static function electronicOptions(): array
    {
        return [
            self::Instapay->value => self::Instapay->label(),
            self::VodafoneCash->value => self::VodafoneCash->label(),
        ];
    }
}
