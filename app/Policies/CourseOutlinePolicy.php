<?php

namespace App\Policies;

use App\Models\CourseOutline;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourseOutlinePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_course::outline');
    }

    public function view(User $user, CourseOutline $courseOutline): bool
    {
        return $user->can('view_course::outline');
    }

    public function create(User $user): bool
    {
        return $user->can('create_course::outline');
    }

    public function update(User $user, CourseOutline $courseOutline): bool
    {
        return $user->can('update_course::outline');
    }

    public function delete(User $user, CourseOutline $courseOutline): bool
    {
        return $user->can('delete_course::outline');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_course::outline');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder_course::outline');
    }
}
