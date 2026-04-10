<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\LegalCase;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('إجمالي العملاء', $this->getTotalClients())
                ->description('جميع العملاء المسجلين')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('إجمالي القضايا', $this->getTotalCases())
                ->description('القضايا القانونية في النظام')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('المدفوعات المعلقة', $this->formatCurrency($this->getPendingAmount()))
                ->description(sprintf('%d فاتورة غير مدفوعة', $this->getPendingPaymentCount()))
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('المدفوعات المكتملة', $this->formatCurrency($this->getPaidAmount()))
                ->description(sprintf('%d دفعات مكتملة', $this->getPaidPaymentCount()))
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('إجمالي الإيرادات', $this->formatCurrency($this->getTotalRevenue()))
                ->description('إجمالي المبلغ المجمع')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }

    private function getTotalClients(): int
    {
        return Client::count();
    }

    private function getTotalCases(): int
    {
        return LegalCase::count();
    }

    private function getPendingAmount(): float
    {
        return (float) Payment::where('status', PaymentStatus::Pending->value)
            ->sum('amount');
    }

    private function getPendingPaymentCount(): int
    {
        return Payment::where('status', PaymentStatus::Pending->value)->count();
    }

    private function getPaidAmount(): float
    {
        return (float) Payment::where('status', PaymentStatus::Paid->value)
            ->sum('amount');
    }

    private function getPaidPaymentCount(): int
    {
        return Payment::where('status', PaymentStatus::Paid->value)->count();
    }

    private function getTotalRevenue(): float
    {
        return (float) Payment::where('status', PaymentStatus::Paid->value)
            ->sum('amount');
    }

    private function formatCurrency(float $amount): string
    {
        return 'EGP ' . number_format($amount, 2);
    }
}
