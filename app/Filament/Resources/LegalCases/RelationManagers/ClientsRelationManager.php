<?php

namespace App\Filament\Resources\LegalCases\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientsRelationManager extends RelationManager
{
    protected static string $relationship = 'clients';

    private static function formatPhoneForWhatsApp(string $phone): string
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
    protected static ?string $title = 'العملاء';
    protected static ?string $modelLabel = 'عميل';
    protected static ?string $pluralModelLabel = 'العملاء';
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('الإسم')
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->label('الإيميل')
                    ->maxLength(255),
                TextInput::make('phone')
                    ->tel()
                    ->label('رقم الموبايل')
                    ->required()
                    ->rule('regex:/^01[0-9]{9}$/')
                    ->maxLength(255),
                TextInput::make('address')
                    ->label('العنوان')
                    ->maxLength(255),
                TextInput::make('national_id')
                    ->label('الرقم القومي')
                    ->maxLength(255),
                TextInput::make('defendant_name')
                    ->label('المدعي عليه')
                    ->maxLength(255),
                FileUpload::make('photo')
                    ->label('الصورة الشخصية')
                    ->image()
                    ->maxSize(1024),
                FileUpload::make('id_front_photo')
                    ->label('البطاقة (أمام)')
                    ->image()
                    ->maxSize(1024),
                FileUpload::make('id_back_photo')
                    ->label('البطاقة (خلف)')
                    ->image()
                    ->maxSize(1024),
                FileUpload::make('power_of_attorney')
                    ->label('التوكيل')
                    ->maxSize(2048),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AttachAction::make(),
            ])
            ->recordActions([
                Action::make('whatsapp')
                    ->label('واتس أب')
                    ->icon('heroicon-m-chat-bubble-left')
                    ->color('success')
                    ->url(fn($record) => 'https://wa.me/' . self::formatPhoneForWhatsApp($record->phone))
                    ->openUrlInNewTab(),
                EditAction::make(),
                DetachAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
