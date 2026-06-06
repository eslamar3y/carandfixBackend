<?php

namespace App\Filament\Resources\Quotation\Pages;

use App\Filament\Resources\Quotation\QuotationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageQuotations extends ManageRecords
{
    protected static string $resource = QuotationResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
