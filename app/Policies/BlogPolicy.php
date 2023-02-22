<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BlogPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function update(User $user, Blog $blog)
    {   
        return $user->id === $blog->user_id
            ? Response::allow()
            : Response::deny('You do not own this post', 403);
        ;
    }
    public function delete(User $user, Blog $blog)
    {
        return $user->id === $blog->user_id
            ? Response::allow()
            : Response::deny('You do not own this post.', 403);
    }
}