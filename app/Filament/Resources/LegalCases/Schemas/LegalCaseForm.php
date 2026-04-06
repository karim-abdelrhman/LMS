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
                    ->label('Case Number')
                    ->required()
                    ->unique(),
                \Filament\Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'open' => 'Open',
                        'closed' => 'Closed',
                    ])
                    ->default('open')
                    ->required(),
                \Filament\Forms\Components\Select::make('court_id')
                    ->label('Court')
                    ->relationship('court', 'name')
                    ->required(),
            ]);
    }
}
