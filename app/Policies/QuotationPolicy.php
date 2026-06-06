<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Quotation;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuotationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Quotation');
    }

    public function view(AuthUser $authUser, Quotation $quotation): bool
    {
        return $authUser->can('View:Quotation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Quotation');
    }

    public function update(AuthUser $authUser, Quotation $quotation): bool
    {
        return $authUser->can('Update:Quotation');
    }

    public function delete(AuthUser $authUser, Quotation $quotation): bool
    {
        return $authUser->can('Delete:Quotation');
    }
}
