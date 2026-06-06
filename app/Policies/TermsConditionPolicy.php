<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TermsCondition;
use Illuminate\Auth\Access\HandlesAuthorization;

class TermsConditionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TermsCondition');
    }

    public function view(AuthUser $authUser, TermsCondition $termsCondition): bool
    {
        return $authUser->can('View:TermsCondition');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TermsCondition');
    }

    public function update(AuthUser $authUser, TermsCondition $termsCondition): bool
    {
        return $authUser->can('Update:TermsCondition');
    }

    public function delete(AuthUser $authUser, TermsCondition $termsCondition): bool
    {
        return $authUser->can('Delete:TermsCondition');
    }

}