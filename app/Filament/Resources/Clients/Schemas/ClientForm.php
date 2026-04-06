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
                Grid::make(1)->schema([
                    // ======================
                    // البيانات الأساسية
                    // ======================
                    Section::make('المعلومات الأساسية')
                        ->columnSpan(3)
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

                            Textarea::make('address')
                                ->label('العنوان')
                                ->required()
                                ->rows(3)
                                ->maxLength(500),

                            TextInput::make('defendant_name')
                                ->label('المدعي عليه')
                                ->placeholder('اختياري')
                                ->maxLength(255),
                        ]),
                ]),
                Grid::make(1)->schema([
                    // ======================
                    // الصور والتوكيلات
                    // ======================
                    Section::make('الصور والتوكيلات')
                        ->columnSpan(1)
                        ->collapsible()
                        ->schema([

                            FileUpload::make('photo')
                                ->label('الصورة الشخصية')
                                ->image()
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
                        ]),
                ])
            ]);
    }
}
