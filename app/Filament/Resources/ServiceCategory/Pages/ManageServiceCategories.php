<?php

namespace App\Filament\Resources\ServiceCategory\Pages;

use App\Filament\Resources\ServiceCategory\ServiceCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageServiceCategories extends ManageRecords
{
    protected static string $resource = ServiceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
