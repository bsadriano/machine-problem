<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateUser
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $user = User::find($args['id']);

        $validator = Validator::make([
            'username' => $args['username'],
            'name' => $args['name'],
            'avatar' => $args['avatar'],
            'email' => $args['email'],
            'password' => $args['password'],
            'password_confirmation' => $args['password_confirmation'],
        ], [
            'username' => [
                'string',
                'required',
                'max:255',
                Rule::unique('users')->ignore($user),
            ],
            'name' => ['string', 'required', 'max:255'],
            'avatar' => ['nullable', 'image'],
            'email' => [
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'max:255',
                'confirmed',
            ],
        ]);

        $attributes = array_filter($validator->validated());

        if ($args['avatar']) {
            $attributes['avatar'] = $args['avatar']->store('avatars');
        }

        $user->update($attributes);

        return $user;
    }
}
