<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Car;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Car');
    }

    public function view(AuthUser $authUser, Car $car): bool
    {
        return $authUser->can('View:Car');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Car');
    }

    public function update(AuthUser $authUser, Car $car): bool
    {
        return $authUser->can('Update:Car');
    }

    public function delete(AuthUser $authUser, Car $car): bool
    {
        return $authUser->can('Delete:Car');
    }

}