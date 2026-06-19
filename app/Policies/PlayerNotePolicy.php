<?php

namespace App\Policies;

use App\Models\PlayerNote;
use App\Models\User;

class PlayerNotePolicy
{
    /**
     * Determine whether the user can create player notes.
     */
    public function create(User $user): bool
    {
        return $user->can('create player notes');
    }

    /**
     * Determine whether the user can delete a player note.
     */
    public function delete(User $user, PlayerNote $playerNote): bool
    {
        return $user->id === $playerNote->author_id || $user->can('delete player notes');
    }

    /**
     * Determine whether the user can view player notes.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }
}
