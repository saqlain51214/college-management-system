<?php

namespace App\Policies;

use App\Models\FeeSlipTemplate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeeSlipTemplatePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_fee::slip::template');
    }

    public function view(User $user, FeeSlipTemplate $feeSlipTemplate): bool
    {
        return $user->can('view_fee::slip::template');
    }

    public function create(User $user): bool
    {
        return $user->can('create_fee::slip::template');
    }

    public function update(User $user, FeeSlipTemplate $feeSlipTemplate): bool
    {
        return $user->can('update_fee::slip::template');
    }

    public function delete(User $user, FeeSlipTemplate $feeSlipTemplate): bool
    {
        return $user->can('delete_fee::slip::template');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_fee::slip::template');
    }

    public function forceDelete(User $user, FeeSlipTemplate $feeSlipTemplate): bool
    {
        return $user->can('force_delete_fee::slip::template');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_fee::slip::template');
    }

    public function restore(User $user, FeeSlipTemplate $feeSlipTemplate): bool
    {
        return $user->can('restore_fee::slip::template');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_fee::slip::template');
    }

    public function replicate(User $user, FeeSlipTemplate $feeSlipTemplate): bool
    {
        return $user->can('replicate_fee::slip::template');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder_fee::slip::template');
    }
}
