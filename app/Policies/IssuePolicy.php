<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Issue;
use Illuminate\Auth\Access\HandlesAuthorization;

class IssuePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Issue');
    }

    public function view(AuthUser $authUser, Issue $issue): bool
    {
        return $authUser->can('View:Issue');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Issue');
    }

    public function update(AuthUser $authUser, Issue $issue): bool
    {
        return $authUser->can('Update:Issue');
    }

    public function delete(AuthUser $authUser, Issue $issue): bool
    {
        return $authUser->can('Delete:Issue');
    }

}