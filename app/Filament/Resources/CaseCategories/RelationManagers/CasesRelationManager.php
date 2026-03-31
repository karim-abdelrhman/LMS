<?php

namespace App\Filament\Resources\CaseCategories\RelationManagers;

use App\Filament\Resources\LegalCases\LegalCaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class CasesRelationManager extends RelationManager
{
    protected static string $relationship = 'legalCases';

    protected static ?string $relatedResource = LegalCaseResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
