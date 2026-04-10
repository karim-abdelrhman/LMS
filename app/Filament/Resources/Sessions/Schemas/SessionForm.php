<?php

namespace App\Filament\Resources\Sessions\Schemas;

use App\Models\LegalCase;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;

class SessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('case_id')
                    ->label('القضية القانونية')
                    ->options(LegalCase::pluck('title', 'id'))
                    ->searchable()
                    ->required(),
                DateTimePicker::make('scheduled_at')
                    ->label('التاريخ والوقت المجدول')
                    ->required(),
                TextInput::make('location')
                    ->label('موقع الجلسة')
                    ->placeholder('مثال: قاعة المحكمة 1'),
                TextInput::make('judge')
                    ->label('اسم القاضي')
                    ->placeholder('مثال: القاضي أحمد حسن'),
                Textarea::make('notes')
                    ->label('ملاحظات الجلسة')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('حالة الجلسة')
                    ->options([
                        'scheduled' => 'مجدول',
                        'completed' => 'مكتمل',
                        'postponed' => 'مؤجل',
                        'cancelled' => 'ملغى',
                    ])
                    ->default('scheduled')
                    ->required(),
            ]);
    }
}
