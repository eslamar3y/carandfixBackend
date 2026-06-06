<?php

namespace App\Filament\Resources\BatteryVoltage\Pages;

use App\Filament\Resources\BatteryVoltage\BatteryVoltageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBatteryVoltages extends ManageRecords
{
    protected static string $resource = BatteryVoltageResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
