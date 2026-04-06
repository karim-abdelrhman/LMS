<?php

namespace App\Filament\Resources\LegalCases\Schemas;

use Filament\Schemas\Schema;

class LegalCaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('case_number')
                    ->label('رقم القضية')
                    ->required()
                    ->unique(),
                \Filament\Forms\Components\TextInput::make('title')
                    ->label('عنوان القضية')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'open' => 'مفتوحة',
                        'closed' => 'مقفوله',
                    ])
                    ->default('open')
                    ->required(),
                \Filament\Forms\Components\Select::make('court_id')
                    ->label('المحكمة')
                    ->relationship('court', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
