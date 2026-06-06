<?php

namespace App\Filament\Resources\Shield;

use BezhanSalleh\FilamentShield\Resources\Roles\RoleResource as ShieldRoleResource;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

class RoleResource extends ShieldRoleResource
{
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereIn('name', ['admin', 'technician']);
    }
}
