<?php

namespace App\Filament\Resources\Part\Pages;

use App\Filament\Resources\Part\RelationManagers\BrandCategoriesRelationManager;
use App\Filament\Resources\Part\PartResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPart extends EditRecord
{
    protected static string $resource = PartResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    public function getRelationManagers(): array
    {
        return [BrandCategoriesRelationManager::class];
    }
}
