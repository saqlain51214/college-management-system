<?php

namespace App\Policies;

use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPostingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_job::posting');
    }

    public function view(User $user, JobPosting $jobPosting): bool
    {
        return $user->can('view_job::posting');
    }

    public function create(User $user): bool
    {
        return $user->can('create_job::posting');
    }

    public function update(User $user, JobPosting $jobPosting): bool
    {
        return $user->can('update_job::posting');
    }

    public function delete(User $user, JobPosting $jobPosting): bool
    {
        return $user->can('delete_job::posting');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_job::posting');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder_job::posting');
    }
}
