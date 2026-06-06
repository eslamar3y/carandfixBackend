<?php

namespace App\Filament\Resources\CarType\Pages;

use App\Filament\Resources\CarType\CarTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCarTypes extends ManageRecords
{
    protected static string $resource = CarTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
