<?php

namespace App\GraphQL\Queries;

class SomeComplexQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        return \App\Models\Tweet::where('body', 'like', '%' . $args['search'] . '%')->get();
    }
}
