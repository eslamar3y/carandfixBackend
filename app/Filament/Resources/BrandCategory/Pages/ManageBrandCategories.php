<?php

namespace App\Filament\Resources\BrandCategory\Pages;

use App\Filament\Resources\BrandCategory\BrandCategoryResource;
use App\Models\Part;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBrandCategories extends ManageRecords
{
    protected static string $resource = BrandCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->mutateFormDataBeforeCreate(function (array $data) {
            $data['categorizable_type'] = Part::class;
            return $data;
        })];
    }
}
