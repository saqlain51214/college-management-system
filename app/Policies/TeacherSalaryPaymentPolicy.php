<?php

namespace App\Policies;

use App\Models\TeacherSalaryPayment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeacherSalaryPaymentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_teacher::salary::payment');
    }

    public function view(User $user, TeacherSalaryPayment $teacherSalaryPayment): bool
    {
        return $user->can('view_teacher::salary::payment');
    }

    public function create(User $user): bool
    {
        return $user->can('create_teacher::salary::payment');
    }

    public function update(User $user, TeacherSalaryPayment $teacherSalaryPayment): bool
    {
        return $user->can('update_teacher::salary::payment');
    }

    public function delete(User $user, TeacherSalaryPayment $teacherSalaryPayment): bool
    {
        return $user->can('delete_teacher::salary::payment');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_teacher::salary::payment');
    }

    public function restore(User $user, TeacherSalaryPayment $teacherSalaryPayment): bool
    {
        return $user->can('restore_teacher::salary::payment');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_teacher::salary::payment');
    }
}
