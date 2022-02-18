<?php

namespace App\GraphQL\Queries;

use App\Models\User;

class Follows
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        return User::where('id', $args['id'])->first()->follows()->get();
    }
}
