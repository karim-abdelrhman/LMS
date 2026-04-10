<?php

namespace App\Filament\Resources\LegalCases\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LegalCaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('المعلومات الأساسية')
                    ->columnSpan(2)
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('title')
                            ->label('عنوان القضية')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('case_number')
                            ->label('رقم القضية')
                            ->required()
                            ->unique(),
                        \Filament\Forms\Components\Select::make('status')
                            ->label('الحالة')
                            ->options([
                                'open' => 'مفتوحة',
                                'closed' => 'مقفوله',
                            ])
                            ->default('open')
                            ->required(),
                        \Filament\Forms\Components\Select::make('category_id')
                            ->label('نوع القضية')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        \Filament\Forms\Components\Select::make('court_id')
                            ->label('المحكمة')
                            ->relationship('court', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        \Filament\Forms\Components\Select::make('clients')
                            ->label('العملاء')
                            ->multiple()
                            ->relationship('clients', 'name')
                            ->preload()
                            ->searchable()
                            ->required(),

                        \Filament\Forms\Components\RichEditor::make('description')
                            ->columnSpanFull()
                            ->label('وصف القضية'),
                    ])->columns(2),
                Section::make('المرفقات')
                    ->columnSpan(1)
                    ->schema([
                        Repeater::make('attachments')
                            ->label('مرفقات القضية')
                            ->relationship('attachments')
                            ->schema([
                                FileUpload::make('file')
                                    ->label('الملف')
                                    ->directory('case_attachments')
                                    ->required()
                                    ->openable()
                                    ->downloadable(),

                                Select::make('type')
                                    ->label('نوع المستند')
                                    ->options(\App\Enums\AttachmentType::options())
                                    ->required(),
                            ])
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn($state) => $state['type'] ?? 'مرفق')
                            ->defaultItems(0)
                    ])
            ])->columns(3);
    }
}
