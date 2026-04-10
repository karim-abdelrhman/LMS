<?php

namespace App\Filament\Resources\LegalCases\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class LegalCasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('title')->label('العنوان')->sortable()->searchable(),
                \Filament\Tables\Columns\TextColumn::make('case_number')->label('رقم القضية')->sortable()->searchable(),
                \Filament\Tables\Columns\TextColumn::make('category.name')->label('نوع القضية')->sortable()->searchable(),
                \Filament\Tables\Columns\TextColumn::make('court.name')->label('المحكمة')->sortable()->searchable(),
                \Filament\Tables\Columns\TextColumn::make('attachments_count')
                    ->label('المرفقات')
                    ->counts('attachments')
                    ->toggleable(),
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
