<?php

namespace App\Filament\Resources\TermsCondition\Pages;

use App\Filament\Resources\TermsCondition\TermsConditionResource;
use Filament\Resources\Pages\ManageRecords;

class ManageTermsConditions extends ManageRecords
{
    protected static string $resource = TermsConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
