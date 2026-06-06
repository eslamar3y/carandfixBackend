<?php

namespace App\Filament\Resources\EngineType\Pages;

use App\Filament\Resources\EngineType\EngineTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEngineTypes extends ManageRecords
{
    protected static string $resource = EngineTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
