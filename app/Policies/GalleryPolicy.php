<?php

namespace App\Policies;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GalleryPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Gallery $gallery): bool
    {
        return $user->id === $gallery->user_id;
    }

    public function delete(User $user, Gallery $gallery): bool
    {
        return $user->id === $gallery->user_id;
    }
}
