<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        // Check if the authenticated user's patient ID matches the post's patient ID
        return $user->patient?->id === $post->patient_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        // Check if the authenticated user's patient ID matches the post's patient ID
        return $user->patient?->id === $post->patient_id;
    }
}
