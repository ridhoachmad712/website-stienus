<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Lecturer;
use Illuminate\Auth\Access\HandlesAuthorization;

class LecturerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_lecturer');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lecturer $lecturer): bool
    {
        return $user->can('view_lecturer');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_lecturer');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lecturer $lecturer): bool
    {
        return $user->can('update_lecturer');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lecturer $lecturer): bool
    {
        return $user->can('delete_lecturer');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_lecturer');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Lecturer $lecturer): bool
    {
        return $user->can('force_delete_lecturer');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_lecturer');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Lecturer $lecturer): bool
    {
        return $user->can('restore_lecturer');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_lecturer');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Lecturer $lecturer): bool
    {
        return $user->can('replicate_lecturer');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_lecturer');
    }
}
