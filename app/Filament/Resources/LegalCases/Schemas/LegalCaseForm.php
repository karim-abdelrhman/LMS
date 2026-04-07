<?php

namespace App\Filament\Resources\LegalCases\Schemas;

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
                        \Filament\Forms\Components\Select::make('court_id')
                            ->label('المحكمة')
                            ->relationship('court', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        \Filament\Forms\Components\Select::make('clients')
                            ->label('العملاء')
                            ->columnSpan(2)
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
                        \Filament\Forms\Components\FileUpload::make('attachments')
                            ->label('مرفقات القضية')
                            ->helperText('يمكنك رفع أكثر من ملف متعلق بالقضية')
                            ->multiple()
                            ->reorderable()
                            ->directory('case_attachments')
                            ->maxFiles(10)
                            ->appendFiles() // Add new files to existing ones
                            ->panelLayout('grid') // Display files in grid layout
                            ->uploadingMessage('Uploading files...')
                    ]),
            ])->columns(3);
    }
}
