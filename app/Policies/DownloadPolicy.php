<?php

namespace App\Policies;

use App\Models\Download;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DownloadPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_download');
    }

    public function view(User $user, Download $download): bool
    {
        return $user->can('view_download');
    }

    public function create(User $user): bool
    {
        return $user->can('create_download');
    }

    public function update(User $user, Download $download): bool
    {
        return $user->can('update_download');
    }

    public function delete(User $user, Download $download): bool
    {
        return $user->can('delete_download');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_download');
    }

    public function forceDelete(User $user, Download $download): bool
    {
        return $user->can('force_delete_download');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_download');
    }

    public function restore(User $user, Download $download): bool
    {
        return $user->can('restore_download');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_download');
    }

    public function replicate(User $user, Download $download): bool
    {
        return $user->can('replicate_download');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder_download');
    }
}
