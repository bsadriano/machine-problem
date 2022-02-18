<?php

namespace App\GraphQL\Queries;

use App\Models\Tweet;
use App\Models\User;

class UserProfile
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $auth_user = User::find($args['auth_user_id']);
        $user = User::where('username', $args['username'])->first();

        return [
            "user" => $user,
            "can_edit" => $auth_user->can('edit', $user),
            "can_follow" => $auth_user->can('follow', $user),
            "is_following" => $auth_user->following($user)
        ];
    }
}
