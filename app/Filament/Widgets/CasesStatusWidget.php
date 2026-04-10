<?php

namespace App\Filament\Widgets;

use App\Enums\CaseStatus;
use App\Models\LegalCase;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CasesStatusWidget extends ChartWidget
{
    protected  ?string $heading = 'Cases Status Breakdown';

    protected static ?int $sort = 2;

    protected  ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $caseStats = $this->getCaseStatusStats();

        return [
            'datasets' => [
                [
                    'label' => 'Cases',
                    'data' => $caseStats['counts'],
                    'backgroundColor' => [
                        '#f59e0b', // Open - Amber/Warning
                        '#3b82f6', // In Progress - Blue (alternative if needed)
                        '#10b981', // Closed - Green/Success
                        '#8b5cf6', // Won - Purple
                    ],
                    'borderColor' => [
                        '#d97706',
                        '#1e40af',
                        '#059669',
                        '#7c3aed',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $caseStats['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    private function getCaseStatusStats(): array
    {
        $stats = LegalCase::query()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $open = $stats[CaseStatus::OPEN->value]?->count ?? 0;
        $closed = $stats[CaseStatus::CLOSED->value]?->count ?? 0;
        $won = $stats[CaseStatus::WON->value]?->count ?? 0;

        return [
            'labels' => [
                'مفتوحة (Open)',
                'مقفولة (Closed)',
                'مكتسبة (Won)',
            ],
            'counts' => [$open, $closed, $won],
        ];
    }
}
