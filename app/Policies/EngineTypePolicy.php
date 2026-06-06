<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EngineType;
use Illuminate\Auth\Access\HandlesAuthorization;

class EngineTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EngineType');
    }

    public function view(AuthUser $authUser, EngineType $engineType): bool
    {
        return $authUser->can('View:EngineType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EngineType');
    }

    public function update(AuthUser $authUser, EngineType $engineType): bool
    {
        return $authUser->can('Update:EngineType');
    }

    public function delete(AuthUser $authUser, EngineType $engineType): bool
    {
        return $authUser->can('Delete:EngineType');
    }

}