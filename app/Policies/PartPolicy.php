<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Part;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Part');
    }

    public function view(AuthUser $authUser, Part $part): bool
    {
        return $authUser->can('View:Part');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Part');
    }

    public function update(AuthUser $authUser, Part $part): bool
    {
        return $authUser->can('Update:Part');
    }

    public function delete(AuthUser $authUser, Part $part): bool
    {
        return $authUser->can('Delete:Part');
    }

}