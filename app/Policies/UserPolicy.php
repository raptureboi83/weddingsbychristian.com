<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return (bool) $user->is_admin;
    }

    public function view(User $user, User $model): bool
    {
        return (bool) $user->is_admin;
    }

    public function create(User $user): bool
    {
        return (bool) $user->is_admin;
    }

    public function update(User $user, User $model): bool
    {
        return (bool) $user->is_admin;
    }

    public function delete(User $user, User $model): bool
    {
        if (! $user->is_admin) {
            return false;
        }

        if ($user->id === $model->id) {
            return false;
        }

        if ($model->is_admin && User::query()->where('is_admin', true)->count() <= 1) {
            return false;
        }

        return true;
    }

    public function deleteAny(User $user): bool
    {
        return (bool) $user->is_admin;
    }

    public function restore(User $user, User $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }
}