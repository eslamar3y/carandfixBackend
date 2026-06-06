<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CarType;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CarType');
    }

    public function view(AuthUser $authUser, CarType $carType): bool
    {
        return $authUser->can('View:CarType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CarType');
    }

    public function update(AuthUser $authUser, CarType $carType): bool
    {
        return $authUser->can('Update:CarType');
    }

    public function delete(AuthUser $authUser, CarType $carType): bool
    {
        return $authUser->can('Delete:CarType');
    }

}