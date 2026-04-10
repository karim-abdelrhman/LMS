<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\CasesStatusWidget;
use App\Filament\Widgets\PaymentsSummaryWidget;
use App\Filament\Widgets\UpcomingSessionsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            CasesStatusWidget::class,
            PaymentsSummaryWidget::class,
            UpcomingSessionsWidget::class,
        ];
    }
}
