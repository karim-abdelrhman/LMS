<?php

namespace App\Filament\Resources\Courts\Schemas;

use App\Models\CourtCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CourtForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Court Name')
                    ->required()
                    ->maxLength(255),
                Select::make('category_id')
                    ->label('Court Category')
                    ->options(CourtCategory::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
            ]);
    }
}
