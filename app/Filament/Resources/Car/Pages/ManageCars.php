<?php

namespace App\Filament\Resources\Car\Pages;

use App\Filament\Resources\Car\CarResource;
use App\Models\Car;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ManageCars extends ManageRecords
{
    protected static string $resource = CarResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }

    public function getTabs(): array
    {
        return [
            __('All') => Tab::make()
                ->badge(Car::count()),
            __('New') => Tab::make()
                ->badge(Car::where('status', 'pending')->count())
                ->query(fn($query) => $query->where('status', 'pending')),
            __('Active') => Tab::make()
                ->badge(Car::where('status', 'approved')->count())
                ->query(fn($query) => $query->where('status', 'approved')),
            __('Rejected') => Tab::make()
                ->badge(Car::where('status', 'rejected')->count())
                ->badgeColor('danger')
                ->query(fn($query) => $query->where('status', 'rejected')),
        ];
    }
}
