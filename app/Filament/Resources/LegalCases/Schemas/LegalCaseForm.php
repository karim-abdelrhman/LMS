<?php

namespace App\Filament\Resources\LegalCases\Schemas;

use App\Enums\AttachmentType;
use App\Enums\CaseStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LegalCaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('المعلومات الأساسية')
                    ->collapsible()
                    ->collapsed(false)
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('عنوان القضية')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('case_number')
                            ->label('رقم القضية')
                            ->required()
                            ->unique(),
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Select::make('status')
                                    ->label('الحالة')
                                    ->options([
                                        CaseStatus::OPEN->value => CaseStatus::OPEN->label(),
                                        CaseStatus::CLOSED->value => CaseStatus::CLOSED->label(),
                                        CaseStatus::WON->value => CaseStatus::WON->label(),
                                    ])
                                    ->default(CaseStatus::OPEN->value)
                                    ->required(),

                                Select::make('category_id')
                                    ->label('نوع القضية')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('court_id')
                                    ->label('المحكمة')
                                    ->relationship('court', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),


                        RichEditor::make('description')
                            ->columnSpanFull()
                            ->label('وصف القضية'),
                    ])->columns(2),
                Section::make('المرفقات')
                    ->collapsible()
                    ->collapsed(false)
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
                                    ->options(AttachmentType::options())
                                    ->required(),
                            ])
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn($state) => $state['type'] ?? 'مرفق')
                            ->defaultItems(0),
                    ]),
            ])->columns(3);
    }
}
