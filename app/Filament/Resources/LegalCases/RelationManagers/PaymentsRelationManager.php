<?php

namespace App\Filament\Resources\LegalCases\RelationManagers;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\Payment;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'المدفوعات';
    protected static ?string $modelLabel = 'المدفوعات';

    protected static ?string $pluralModelLabel = 'المدفوعات';
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('method')
                    ->label('طريقة الدفع')
                    ->options(PaymentMethod::options())
                    ->live()
                    ->required()
                    ->afterStateUpdated(function (Component $_component, mixed $state, Set $set): void {
                        if ($state === PaymentMethod::Cash->value) {
                            $set('proof_image', null);
                        }
                    }),
                FileUpload::make('proof_image')
                    ->label('صورة الإثبات')
                    ->image()
                    ->disk('public')
                    ->directory('payment_proofs')
                    ->imagePreviewHeight('120')
                    ->hidden(fn(Get $get): bool => blank($get('method')) || $get('method') === PaymentMethod::Cash->value)
                    ->required(fn(Get $get): bool => filled($get('method')) && $get('method') !== PaymentMethod::Cash->value)
                    ->dehydrated(fn(Get $get): bool => filled($get('method')) && $get('method') !== PaymentMethod::Cash->value),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('client.name')
                    ->label('العميل')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('المبلغ')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->formatStateUsing(fn(PaymentType $state): string => $state->label())
                    ->color(fn(PaymentType $state): string => $state->color()),
                TextColumn::make('method')
                    ->label('الطريقة')
                    ->badge()
                    ->formatStateUsing(fn(?PaymentMethod $state): string => $state?->label() ?? '—')
                    ->color(fn(?PaymentMethod $state): string => $state?->color() ?? 'gray'),
                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn(PaymentStatus $state): string => $state->label())
                    ->color(fn(PaymentStatus $state): string => $state->color()),
                ImageColumn::make('proof_image')
                    ->label('إثبات')
                    ->visibility('public')
                    ->square()
                    ->checkFileExistence(false)
                    ->extraImgAttributes(['class' => 'rounded-md']),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options(PaymentStatus::options()),
            ])
            ->headerActions([])
            ->recordActions([
                Action::make('markCashPaid')
                    ->label('دفع نقدي')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn(Payment $record): bool => $record->status === PaymentStatus::Pending)
                    ->requiresConfirmation()
                    ->modalHeading('تسجيل دفع نقدي')
                    ->modalDescription('سيتم إغلاق الدفعة كمدفوعة نقداً دون إثبات صورة.')
                    ->successNotificationTitle('تم تسجيل الدفع النقدي')
                    ->action(function (Payment $record): void {
                        $record->update([
                            'method' => PaymentMethod::Cash,
                            'proof_image' => null,
                            'status' => PaymentStatus::Paid,
                            'paid_at' => now(),
                            'verified_at' => now(),
                            'verified_by' => Auth::id(),
                        ]);
                    }),
                Action::make('uploadProofConfirm')
                    ->label('إثبات وتأكيد')
                    ->icon('heroicon-o-photo')
                    ->color('primary')
                    ->visible(fn(Payment $record): bool => $record->status === PaymentStatus::Pending)
                    ->modalHeading('رفع إثبات وتأكيد الدفع')
                    ->modalWidth('md')
                    ->fillForm(fn(Payment $record): array => [
                        'method' => $record->method?->value,
                        'proof_image' => $record->proof_image,
                    ])
                    ->schema([
                        Select::make('method')
                            ->label('طريقة التحويل')
                            ->options(PaymentMethod::electronicOptions())
                            ->required()
                            ->native(false),
                        FileUpload::make('proof_image')
                            ->label('صورة الإثبات')
                            ->image()
                            ->disk('public')
                            ->directory('payment_proofs')
                            ->required()
                            ->imagePreviewHeight('160'),
                    ])
                    ->successNotificationTitle('تم حفظ الإثبات وتأكيد الدفع')
                    ->action(function (Payment $record, array $data): void {
                        $record->update([
                            'method' => PaymentMethod::from($data['method']),
                            'proof_image' => $data['proof_image'],
                            'status' => PaymentStatus::Paid,
                            'paid_at' => now(),
                            'verified_at' => now(),
                            'verified_by' => Auth::id(),
                        ]);
                    }),
                Action::make('markReviewed')
                    ->label('اعتماد المراجعة')
                    ->icon('heroicon-o-shield-check')
                    ->color('warning')
                    ->visible(function (Payment $record): bool {
                        if ($record->status !== PaymentStatus::Pending) {
                            return false;
                        }

                        if (blank($record->proof_image)) {
                            return false;
                        }

                        return in_array($record->method, [PaymentMethod::Instapay, PaymentMethod::VodafoneCash], true);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('اعتماد الدفع')
                    ->modalDescription('تأكيد أن إثبات التحويل صحيح وإغلاق الدفعة كمدفوعة.')
                    ->successNotificationTitle('تم اعتماد الدفع')
                    ->action(function (Payment $record): void {
                        $record->update([
                            'status' => PaymentStatus::Paid,
                            'paid_at' => now(),
                            'verified_at' => now(),
                            'verified_by' => Auth::id(),
                        ]);
                    }),
                EditAction::make()
                    ->label('تعديل')
                    ->icon('heroicon-o-pencil')
                    ->visible(fn(Payment $record): bool => $record->status === PaymentStatus::Pending)
                    ->successNotificationTitle('تم حفظ بيانات الدفع')
                    ->mutateRecordDataUsing(function (array $data): array {
                        if (($data['method'] ?? null) === PaymentMethod::Cash->value) {
                            $data['proof_image'] = null;
                        }

                        return $data;
                    }),
            ])
            ->toolbarActions([]);
    }
}
