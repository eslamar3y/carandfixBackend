<?php

namespace App\Filament\Resources\BrandCategory\Pages;

use App\Filament\Resources\BrandCategory\BrandCategoryResource;
use App\Filament\Resources\BrandCategory\RelationManagers\BrandsRelationManager;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBrandCategory extends EditRecord
{
    protected static string $resource = BrandCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    public function getRelationManagers(): array
    {
        return [BrandsRelationManager::class];
    }
}
