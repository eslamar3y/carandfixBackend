<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Emergency;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmergencyPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Emergency');
    }

    public function view(AuthUser $authUser, Emergency $emergency): bool
    {
        return $authUser->can('View:Emergency');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Emergency');
    }

    public function update(AuthUser $authUser, Emergency $emergency): bool
    {
        return $authUser->can('Update:Emergency');
    }

    public function delete(AuthUser $authUser, Emergency $emergency): bool
    {
        return $authUser->can('Delete:Emergency');
    }

}