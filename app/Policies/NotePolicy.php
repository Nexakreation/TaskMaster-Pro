<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;

class NotePolicy
{
    public function delete(User $user, Note $note)
    {
        return $user->id === $note->user_id;
    }

    public function update(User $user, Note $note)
    {
        return $user->id === $note->user_id;
    }
} 