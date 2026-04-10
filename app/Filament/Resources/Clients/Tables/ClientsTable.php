<?php

namespace App\Filament\Resources\Clients\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientsTable
{
    private static function formatPhoneForWhatsApp(string $phone = null): string
    {
        // Remove spaces, dashes, and other characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // If phone starts with 0, replace with 20 (Egypt country code)
        if (str_starts_with($phone, '0')) {
            $phone = '20' . substr($phone, 1);
        }
        
        // Ensure it starts with country code
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }
        
        return $phone;
    }

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
                TextColumn::make('defendant_name')
                    ->label('المدعي عليه')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('whatsapp')
                    ->label('واتس أب')
                    ->icon('heroicon-m-chat-bubble-left')
                    ->color('success')
                    ->url(fn($record) => 'https://wa.me/' . self::formatPhoneForWhatsApp($record->phone))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
