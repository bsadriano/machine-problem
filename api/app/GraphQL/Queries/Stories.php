<?php

namespace App\GraphQL\Queries;

use App\Models\Story;
use App\Models\User;
use Carbon\Carbon;

class Stories
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $user = User::find($args['auth_user_id']);

        $friends = $user->follows()->pluck('id');

        $stories = Story::whereIn('user_id', $friends)
            ->orWhere('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->withSeen($user->id)
            ->with(['user'])
            ->orderByDesc('id')
            ->get()
            ->toArray();

        $reduced =  array_reduce($stories, function (array $acc, array $element) {
            $index = $element['user_id'];

            $posts = isset($acc[$index]) ? [
                ...$acc[$index]['posts'],
                ['story_id' => $element['id'], 'text' => $element['body'], 'seen' => (bool) $element['seen_id']]
            ] : [
                ['story_id' => $element['id'], 'text' => $element['body'], 'seen' => (bool) $element['seen_id']]
            ];

            $acc[$index] = [
                'user' => $element['user']['name'],
                'posts' => $posts,
            ];
            return $acc;
        }, []);

        $formatted = [];

        foreach ($reduced as $val) {
            $formatted[] = $val;
        }

        return $formatted;
    }
}
