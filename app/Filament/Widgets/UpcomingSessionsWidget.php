<?php

namespace App\Filament\Widgets;

use App\Models\LegalSession;
use Filament\Widgets\Widget;

class UpcomingSessionsWidget extends Widget
{
    protected string $view = 'filament.widgets.upcoming-sessions-widget';

    protected static ?int $sort = 4;

    protected static ?string $maxHeight = '350px';

    public function getUpcomingSessions(): array
    {
        $sessions = LegalSession::query()
            ->with('legalCase')
            ->upcoming()
            ->limit(10)
            ->get();

        if ($sessions->isEmpty()) {
            return [];
        }

        return $sessions->map(function (LegalSession $session) {
            return [
                'id' => $session->id,
                'case_title' => $session->legalCase?->title ?? 'N/A',
                'scheduled_at' => $session->getFormattedDateAttribute(),
                'location' => $session->location ?? 'Not specified',
                'judge' => $session->judge ?? 'Not assigned',
                'status' => $session->getStatusLabelAttribute(),
                'status_color' => $session->getStatusColorAttribute(),
            ];
        })->toArray();
    }

    public function hasUpcomingSessions(): bool
    {
        return !empty($this->getUpcomingSessions());
    }
}
