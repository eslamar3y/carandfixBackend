<?php

namespace App\Filament\Resources\AboutAndContact\Pages;

use App\Filament\Resources\AboutAndContact\AboutAndContactResource;
use Filament\Resources\Pages\ManageRecords;

class ManageAboutAndContacts extends ManageRecords
{
    protected static string $resource = AboutAndContactResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
