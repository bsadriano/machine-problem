<?php

namespace App\GraphQL\Mutations;

use App\Models\Story;
use App\Models\User;

class SeeStory
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $story = Story::find($args['story_id']);
        $user = User::find($args['user_id']);
        return $story->view($user);
    }
}
