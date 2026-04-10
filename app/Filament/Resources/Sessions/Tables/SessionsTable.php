<?php

namespace App\Filament\Resources\Sessions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class SessionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('الرقم')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('legalCase.title')
                    ->label('القضية')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('scheduled_at')
                    ->label('التاريخ والوقت')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                TextColumn::make('location')
                    ->label('الموقع')
                    ->searchable(),
                TextColumn::make('judge')
                    ->label('القاضي')
                    ->searchable(),
                BadgeColumn::make('status')
                    ->label('الحالة')
                    ->colors([
                        'warning' => 'scheduled',
                        'success' => 'completed',
                        'info' => 'postponed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn($state) => match ($state) {
                        'scheduled' => 'مجدول',
                        'completed' => 'مكتمل',
                        'postponed' => 'مؤجل',
                        'cancelled' => 'ملغى',
                        default => $state,
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'مجدول',
                        'completed' => 'مكتمل',
                        'postponed' => 'مؤجل',
                        'cancelled' => 'ملغى',
                    ]),
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
