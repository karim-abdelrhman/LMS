<?php

namespace App\Filament\Resources\CourtCategories\Pages;

use App\Filament\Resources\CourtCategories\CourtCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCourtCategory extends EditRecord
{
    protected static string $resource = CourtCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
