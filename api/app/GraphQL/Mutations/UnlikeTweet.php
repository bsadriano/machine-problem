<?php

namespace App\GraphQL\Mutations;

use App\Models\Tweet;
use App\Models\User;

class UnlikeTweet
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $tweet = Tweet::find($args['tweet_id']);
        $tweet->unlike(User::find($args['user_id']));
        return $tweet;
    }
}
