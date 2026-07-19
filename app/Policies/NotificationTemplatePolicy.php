<?php

namespace App\Policies;

use App\Models\NotificationTemplate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationTemplatePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_notification::template');
    }

    public function view(User $user, NotificationTemplate $notificationTemplate): bool
    {
        return $user->can('view_notification::template');
    }

    public function create(User $user): bool
    {
        return $user->can('create_notification::template');
    }

    public function update(User $user, NotificationTemplate $notificationTemplate): bool
    {
        return $user->can('update_notification::template');
    }

    public function delete(User $user, NotificationTemplate $notificationTemplate): bool
    {
        return $user->can('delete_notification::template');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_notification::template');
    }

    public function forceDelete(User $user, NotificationTemplate $notificationTemplate): bool
    {
        return $user->can('force_delete_notification::template');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_notification::template');
    }

    public function restore(User $user, NotificationTemplate $notificationTemplate): bool
    {
        return $user->can('restore_notification::template');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_notification::template');
    }

    public function replicate(User $user, NotificationTemplate $notificationTemplate): bool
    {
        return $user->can('replicate_notification::template');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder_notification::template');
    }
}
