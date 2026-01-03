<?php

namespace App\Policies;

use App\Models\LiveSession;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LiveSessionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LiveSession $liveSession): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LiveSession $liveSession): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LiveSession $liveSession): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LiveSession $liveSession): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LiveSession $liveSession): bool
    {
        return false;
    }

    /**
     * Determine whether the user can join the live session.
     */
    public function join(User $user, LiveSession $liveSession): bool
    {
        // A student can join if they have been approved, which means a record
        // exists for them in the session_participants table for this session.
        return $liveSession->participants()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can access chat for the live session.
     */
    public function accessChat(User $user, LiveSession $liveSession): bool
    {
        // Check if user is the teacher of this session
        if ($liveSession->teacher_id === $user->id) {
            return true;
        }

        // Check if user is a student enrolled in this session
        if ($user->role === \App\Enums\UserRole::Student->value) {
            return $liveSession->participants()->where('user_id', $user->id)->exists();
        }

        return false;
    }
}
