<?php

namespace App\Policies;

use App\Models\Diploma;
use App\Models\User;

class DiplomaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_diploma');
    }

    public function view(User $user, Diploma $diploma): bool
    {
        return $user->can('view_diploma');
    }

    public function create(User $user): bool
    {
        return $user->can('create_diploma');
    }

    public function update(User $user, Diploma $diploma): bool
    {
        return in_array($diploma->status, ['draft', 'revoked'], true) && $user->can('update_diploma');
    }

    public function issue(User $user, Diploma $diploma): bool
    {
        return $diploma->status === 'draft' && $user->can('issue_diploma');
    }

    public function revoke(User $user, Diploma $diploma): bool
    {
        return $diploma->status === 'issued' && $user->can('revoke_diploma');
    }

    public function download(User $user, Diploma $diploma): bool
    {
        return $diploma->status === 'issued' && $user->can('download_diploma');
    }

    public function delete(User $user, Diploma $diploma): bool
    {
        // Only draft or revoked records are removable; a revoked one needs lifecycle authority.
        return match ($diploma->status) {
            'draft' => $user->can('update_diploma'),
            'revoked' => $user->can('revoke_diploma'),
            default => false,
        };
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
