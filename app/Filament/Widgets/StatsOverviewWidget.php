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
            Stat::make('Total Clients', $this->getTotalClients())
                ->description('All registered clients')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Total Cases', $this->getTotalCases())
                ->description('Legal cases in system')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Pending Payments', $this->formatCurrency($this->getPendingAmount()))
                ->description(sprintf('%d unpaid invoices', $this->getPendingPaymentCount()))
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Paid Payments', $this->formatCurrency($this->getPaidAmount()))
                ->description(sprintf('%d completed payments', $this->getPaidPaymentCount()))
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Total Revenue', $this->formatCurrency($this->getTotalRevenue()))
                ->description('Total amount collected')
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
