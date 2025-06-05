<?php

namespace App\Policies;

use App\Models\User;
use App\Models\QRGenerator;
use Illuminate\Auth\Access\HandlesAuthorization;

class QRGeneratorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_q::r::generator::q::r::generator');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, QRGenerator $qRGenerator): bool
    {
        return $user->can('view_q::r::generator::q::r::generator');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_q::r::generator::q::r::generator');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, QRGenerator $qRGenerator): bool
    {
        return $user->can('update_q::r::generator::q::r::generator');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, QRGenerator $qRGenerator): bool
    {
        return $user->can('delete_q::r::generator::q::r::generator');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_q::r::generator::q::r::generator');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, QRGenerator $qRGenerator): bool
    {
        return $user->can('force_delete_q::r::generator::q::r::generator');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_q::r::generator::q::r::generator');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, QRGenerator $qRGenerator): bool
    {
        return $user->can('restore_q::r::generator::q::r::generator');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_q::r::generator::q::r::generator');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, QRGenerator $qRGenerator): bool
    {
        return $user->can('replicate_q::r::generator::q::r::generator');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_q::r::generator::q::r::generator');
    }
}
