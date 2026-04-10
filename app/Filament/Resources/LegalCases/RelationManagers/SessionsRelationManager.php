<?php

namespace App\Filament\Resources\LegalCases\RelationManagers;

use App\Models\LegalSession;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SessionsRelationManager extends RelationManager
{
    protected static string $relationship = 'legalSessions';

    protected static ?string $title = 'الجلسات';
    protected static ?string $modelLabel = 'جلسة';
    protected static ?string $pluralModelLabel = 'جلسات';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('scheduled_at')
                    ->label('موعد الجلسة')
                    ->required(),
                Textarea::make('notes')
                    ->label('ملاحظات')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'scheduled' => 'مجدول',
                        'completed' => 'مكتمل',
                        'postponed' => 'مؤجل',
                        'cancelled' => 'ملغاة',
                    ])
                    ->default('scheduled')
                    ->required(),
                DateTimePicker::make('next_session_at')
                    ->label('الجلسة القادمة')
                    ->hint('تاريخ الجلسة المقبلة المتوقعة'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('scheduled_at')
                    ->label('موعد الجلسة')
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
                        'cancelled' => 'ملغاة',
                        default => $state,
                    }),
                TextColumn::make('notes')
                    ->label('ملاحظات')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('next_session_at')
                    ->label('الجلسة القادمة')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                Action::make('completed')
                    ->label('مكتمل')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn($record) => $record->status === 'scheduled')
                    ->form([
                        Textarea::make('notes')
                            ->label('ملاحظات الجلسة'),
                        DateTimePicker::make('next_session_at')
                            ->label('الجلسة القادمة'),
                    ])
                    ->action(function (LegalSession $record, array $data) {
                        $record->update([
                            'status' => 'completed',
                            'notes' => $data['notes'] ?? $record->notes,
                            'next_session_at' => $data['next_session_at'] ?? $record->next_session_at,
                        ]);
                    }),
                Action::make('postpone')
                    ->label('أجل')
                    ->icon('heroicon-m-clock')
                    ->color('info')
                    ->visible(fn($record) => in_array($record->status, ['scheduled', 'completed']))
                    ->form([
                        DateTimePicker::make('next_session_at')
                            ->label('موعد الجلسة الجديد')
                            ->required(),
                        Textarea::make('notes')
                            ->label('سبب التأجيل'),
                    ])
                    ->action(function (LegalSession $record, array $data) {
                        $record->update([
                            'status' => 'postponed',
                            'scheduled_at' => $data['next_session_at'],
                            'next_session_at' => null,
                            'notes' => ($record->notes ? $record->notes . '\n' : '') . 'التأجيل: ' . $data['notes'],
                        ]);
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
