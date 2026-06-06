<?php

namespace App\Filament\Resources\Emergency\Pages;

use App\Filament\Resources\Emergency\EmergencyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEmergencies extends ManageRecords
{
    protected static string $resource = EmergencyResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
