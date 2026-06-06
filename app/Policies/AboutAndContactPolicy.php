<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AboutAndContact;
use Illuminate\Auth\Access\HandlesAuthorization;

class AboutAndContactPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AboutAndContact');
    }

    public function view(AuthUser $authUser, AboutAndContact $aboutAndContact): bool
    {
        return $authUser->can('View:AboutAndContact');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AboutAndContact');
    }

    public function update(AuthUser $authUser, AboutAndContact $aboutAndContact): bool
    {
        return $authUser->can('Update:AboutAndContact');
    }

    public function delete(AuthUser $authUser, AboutAndContact $aboutAndContact): bool
    {
        return $authUser->can('Delete:AboutAndContact');
    }

}