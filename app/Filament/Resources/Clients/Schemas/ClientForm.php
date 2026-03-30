<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Info')
                    ->schema([
                        Grid::make(1)->schema([
                            TextInput::make('name')
                                ->label('Full Name')->required()->maxLength(255),
                            TextInput::make('email')
                                ->label('Email')->nullable()->email(),
                            TextInput::make('phone')->required()->numeric()->maxLength(11),
                            TextInput::make('national_id')
                                ->label('National ID')
                                ->required()
                                ->numeric()
                                ->maxLength(14),
                            Textarea::make('address')
                                ->label('Address')
                                ->required()
                                ->maxLength(500),
                        ]),
                    ]),
                Section::make('Pictures')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Photo')
                            ->image()
                            ->maxSize(1024)
                            ->directory('client-photos'),
                        FileUpload::make('id_front_photo')
                            ->label('ID Front Photo')
                            ->image()
                            ->maxSize(1024)
                            ->directory('client-id-photos'),
                        FileUpload::make('id_back_photo')
                            ->label('ID Back Photo')
                            ->image()
                            ->maxSize(1024)
                            ->directory('client-id-photos'),
                    ]),
            ]);
    }
}
