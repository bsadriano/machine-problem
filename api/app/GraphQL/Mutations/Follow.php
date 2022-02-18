<?php

namespace App\GraphQL\Mutations;

use App\Models\User;

class Follow
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $auth_user = User::find($args['auth_user_id']);
        $user = User::where('username', $args['username'])->first();

        if ($args['is_following']) {
            $auth_user->follow($user);
        } else {
            $auth_user->unfollow($user);
        }

        return $auth_user->following($user);
    }
}
