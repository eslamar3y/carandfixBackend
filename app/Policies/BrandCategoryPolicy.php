<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BrandCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class BrandCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BrandCategory');
    }

    public function view(AuthUser $authUser, BrandCategory $brandCategory): bool
    {
        return $authUser->can('View:BrandCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BrandCategory');
    }

    public function update(AuthUser $authUser, BrandCategory $brandCategory): bool
    {
        return $authUser->can('Update:BrandCategory');
    }

    public function delete(AuthUser $authUser, BrandCategory $brandCategory): bool
    {
        return $authUser->can('Delete:BrandCategory');
    }

}