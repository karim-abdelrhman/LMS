<?php

namespace App\Filament\Resources\LegalCases\Schemas;

use Filament\Schemas\Schema;

class LegalCaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('title')->label('Title')->required()->maxLength(255),
                \Filament\Forms\Components\TextInput::make('case_number')->label('Case Number')->numeric()->required(),
                \Filament\Forms\Components\TextInput::make('case_type')->label('Case Type')->numeric()->required(),
                \Filament\Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('court_id')
                    ->label('Court')
                    ->relationship('court', 'name')
                    ->required(),
            ]);
    }
}
