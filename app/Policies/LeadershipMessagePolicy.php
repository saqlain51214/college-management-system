<?php

namespace App\Policies;

use App\Models\LeadershipMessage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadershipMessagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_leadership::message');
    }

    public function view(User $user, LeadershipMessage $leadershipMessage): bool
    {
        return $user->can('view_leadership::message');
    }

    public function create(User $user): bool
    {
        return $user->can('create_leadership::message');
    }

    public function update(User $user, LeadershipMessage $leadershipMessage): bool
    {
        return $user->can('update_leadership::message');
    }

    public function delete(User $user, LeadershipMessage $leadershipMessage): bool
    {
        return $user->can('delete_leadership::message');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_leadership::message');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder_leadership::message');
    }
}
