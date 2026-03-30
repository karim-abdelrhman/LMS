<?php

namespace App\Filament\Resources\CourtCategories\Pages;

use App\Filament\Resources\CourtCategories\CourtCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCourtCategory extends CreateRecord
{
    protected static string $resource = CourtCategoryResource::class;
}
