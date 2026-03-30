<?php

namespace App\Filament\Resources\CourtCategories\Pages;

use App\Filament\Resources\CourtCategories\CourtCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCourtCategories extends ListRecords
{
    protected static string $resource = CourtCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
