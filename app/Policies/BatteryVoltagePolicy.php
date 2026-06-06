<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BatteryVoltage;
use Illuminate\Auth\Access\HandlesAuthorization;

class BatteryVoltagePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BatteryVoltage');
    }

    public function view(AuthUser $authUser, BatteryVoltage $batteryVoltage): bool
    {
        return $authUser->can('View:BatteryVoltage');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BatteryVoltage');
    }

    public function update(AuthUser $authUser, BatteryVoltage $batteryVoltage): bool
    {
        return $authUser->can('Update:BatteryVoltage');
    }

    public function delete(AuthUser $authUser, BatteryVoltage $batteryVoltage): bool
    {
        return $authUser->can('Delete:BatteryVoltage');
    }

}