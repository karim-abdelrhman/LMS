<?php

namespace App\Filament\Resources\Sessions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('ip_address'),
                Textarea::make('user_agent')
                    ->columnSpanFull(),
                Textarea::make('payload')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('last_activity')
                    ->required()
                    ->numeric(),
            ]);
    }
}
