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
                \Filament\Tables\Columns\TextColumn::make('title')->label('Title')->sortable()->searchable(),
                \Filament\Tables\Columns\TextColumn::make('case_number')->label('Case Number')->sortable()->searchable(),
                \Filament\Tables\Columns\TextColumn::make('case_type')->label('Case Type')->sortable()->searchable(),
                \Filament\Tables\Columns\TextColumn::make('category.name')->label('Category')->sortable()->searchable(),
                \Filament\Tables\Columns\TextColumn::make('court.name')->label('Court')->sortable()->searchable(),
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
