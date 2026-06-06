<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    public function getTabs(): array
    {
        return [
            __('All') => Tab::make()
                ->badge(User::count()),
            __('Customers') => Tab::make()
                ->badge(User::where('role', 'customer')->count())
                ->query(fn($query) => $query->where('role', 'customer')),
            __('Active Customers') => Tab::make()
                ->badge(User::where('role', 'customer')->where('is_active', true)->count())
                ->query(fn($query) => $query->where('role', 'customer')->where('is_active', true)),
            __('Technicians') => Tab::make()
                ->badge(User::where('role', 'technician')->count())
                ->query(fn($query) => $query->where('role', 'technician')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
