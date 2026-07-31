<?php

namespace App\Policies;

use App\Models\FeeRefund;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeeRefundPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_fee::refund');
    }

    public function view(User $user, FeeRefund $feeRefund): bool
    {
        return $user->can('view_fee::refund');
    }

    public function create(User $user): bool
    {
        return $user->can('create_fee::refund');
    }

    public function update(User $user, FeeRefund $feeRefund): bool
    {
        return $user->can('update_fee::refund');
    }

    public function delete(User $user, FeeRefund $feeRefund): bool
    {
        return $user->can('delete_fee::refund');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_fee::refund');
    }

    public function restore(User $user, FeeRefund $feeRefund): bool
    {
        return $user->can('restore_fee::refund');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_fee::refund');
    }
}
