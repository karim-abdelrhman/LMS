<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Support\Enums\IconPosition;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)->columnSpan(2)
                    ->schema([
                        Tabs::make('Client Information')
                            ->tabs([
                                Tabs\Tab::make('المعلومات الرئيسية')
                                    ->icon('heroicon-m-user')
                                    ->iconPosition(IconPosition::After)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('الإسم بالكامل')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('email')
                                            ->label('الإيميل')
                                            ->email()
                                            ->unique(ignoreRecord: true)
                                            ->nullable(),

                                        TextInput::make('phone')
                                            ->label('رقم الموبايل')
                                            ->required()
                                            ->tel()
                                            ->rule('regex:/^01[0-9]{9}$/') // رقم مصري
                                            ->maxLength(11),

                                        TextInput::make('national_id')
                                            ->label('الرقم القومي')
                                            ->required()
                                            // ->digits(14)
                                            ->unique(ignoreRecord: true),

                                        TextInput::make('address')
                                            ->label('العنوان')
                                            ->required(),

                                        TextInput::make('defendant_name')
                                            ->label('المدعي عليه')
                                            ->placeholder('اختياري')
                                            ->maxLength(255),
                                    ])->columns(3),

                                Tabs\Tab::make('الصور والتوكيلات')
                                    ->icon('heroicon-m-photo')
                                    ->iconPosition(IconPosition::After)
                                    ->schema([
                                        FileUpload::make('photo')
                                            ->label('الصورة الشخصية')
                                            ->image()
                                            ->avatar()
                                            ->imagePreviewHeight('120')
                                            ->imageEditor() // crop + edit
                                            ->directory('client-photos')
                                            ->maxSize(1024),

                                        FileUpload::make('id_front_photo')
                                            ->label('البطاقة (أمام)')
                                            ->image()
                                            ->imagePreviewHeight('120')
                                            ->directory('client-id-photos')
                                            ->maxSize(1024)
                                            ->required(),

                                        FileUpload::make('id_back_photo')
                                            ->label('البطاقة (خلف)')
                                            ->image()
                                            ->imagePreviewHeight('120')
                                            ->directory('client-id-photos')
                                            ->maxSize(1024)
                                            ->required(),

                                        FileUpload::make('power_of_attorney')
                                            ->label('التوكيل')
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->directory('client-documents')
                                            ->maxSize(2048),
                                    ])->columns(4),
                                Tabs\Tab::make('الأتعاب')
                                    ->icon('heroicon-m-currency-dollar')
                                    ->iconPosition(IconPosition::After)
                                    ->schema([
                                        TextInput::make('fees')
                                            ->label('الأتعاب المتفق عليها')
                                            ->numeric()
                                            ->prefix('$')
                                            ->step(0.01),
                                    ])->columns(1),
                            ]),
                    ]),
            ])->columns(1);
    }
}
