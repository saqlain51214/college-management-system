<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobApplicationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_job::application');
    }

    public function view(User $user, JobApplication $jobApplication): bool
    {
        return $user->can('view_job::application');
    }

    public function create(User $user): bool
    {
        return $user->can('create_job::application');
    }

    public function update(User $user, JobApplication $jobApplication): bool
    {
        return $user->can('update_job::application');
    }

    public function delete(User $user, JobApplication $jobApplication): bool
    {
        return $user->can('delete_job::application');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_job::application');
    }
}
