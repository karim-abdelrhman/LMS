<?php

namespace App\Filament\Resources\CourtCategories;

use App\Filament\Resources\CourtCategories\Pages\CreateCourtCategory;
use App\Filament\Resources\CourtCategories\Pages\EditCourtCategory;
use App\Filament\Resources\CourtCategories\Pages\ListCourtCategories;
use App\Filament\Resources\CourtCategories\RelationManagers\CourtsRelationManager;
use App\Filament\Resources\CourtCategories\Schemas\CourtCategoryForm;
use App\Filament\Resources\CourtCategories\Tables\CourtCategoriesTable;
use App\Models\CourtCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CourtCategoryResource extends Resource
{
    protected static ?string $model = CourtCategory::class;
    protected static string|UnitEnum|null $navigationGroup = 'المحاكم ';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Squares2x2;
    protected static ?string $navigationLabel = 'أنواع المحاكم';
    protected static ?string $modelLabel = 'فئة المحكمة';
    protected static ?string $pluralModelLabel = 'فئات المحاكم';

    protected static ?string $recordTitleAttribute = 'courtCategory';

    public static function form(Schema $schema): Schema
    {
        return CourtCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CourtCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CourtsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCourtCategories::route('/'),
            'create' => CreateCourtCategory::route('/create'),
            'edit' => EditCourtCategory::route('/{record}/edit'),
        ];
    }
}
