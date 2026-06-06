<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Notification;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Notification');
    }

    public function view(AuthUser $authUser, Notification $notification): bool
    {
        return $authUser->can('View:Notification');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Notification');
    }

    public function update(AuthUser $authUser, Notification $notification): bool
    {
        return $authUser->can('Update:Notification');
    }

    public function delete(AuthUser $authUser, Notification $notification): bool
    {
        return $authUser->can('Delete:Notification');
    }

}