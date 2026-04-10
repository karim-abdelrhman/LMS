<?php

namespace App\Filament\Resources\Clients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('الاسم')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('الإيميل')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('رقم الموبايل')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('national_id')
                    ->label('الرقم القومي')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('address')
                    ->label('العنوان')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('defendant_name')
                    ->label('المدعي عليه')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
