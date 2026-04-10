<?php

namespace App\Filament\Widgets;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use Filament\Widgets\ChartWidget;

class PaymentsSummaryWidget extends ChartWidget
{
    protected  ?string $heading = 'Payments Summary by Method';

    protected static ?int $sort = 3;

    protected  ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $paymentStats = $this->getPaymentSummary();

        return [
            'datasets' => [
                [
                    'label' => 'Collected (EGP)',
                    'data' => $paymentStats['collected'],
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#059669',
                    'borderWidth' => 2,
                    'fill' => false,
                    'tension' => 0.1,
                    'type' => 'line',
                ],
                [
                    'label' => 'Pending (EGP)',
                    'data' => $paymentStats['pending'],
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#d97706',
                    'borderWidth' => 2,
                    'fill' => false,
                    'tension' => 0.1,
                    'type' => 'line',
                ],
            ],
            'labels' => $paymentStats['methods'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getPaymentSummary(): array
    {
        $methods = [
            PaymentMethod::Cash->value => 'نقدي (Cash)',
            PaymentMethod::Instapay->value => 'إنستاباي (Instapay)',
            PaymentMethod::VodafoneCash->value => 'فودافون كاش (Vodafone Cash)',
        ];

        $collected = [];
        $pending = [];

        foreach (array_keys($methods) as $method) {
            // Collected (Paid) payments
            $collectedAmount = (float) Payment::where('method', $method)
                ->where('status', PaymentStatus::Paid->value)
                ->sum('amount');
            $collected[] = $collectedAmount;

            // Pending payments
            $pendingAmount = (float) Payment::where('method', $method)
                ->where('status', PaymentStatus::Pending->value)
                ->sum('amount');
            $pending[] = $pendingAmount;
        }

        return [
            'methods' => array_values($methods),
            'collected' => $collected,
            'pending' => $pending,
        ];
    }
}
