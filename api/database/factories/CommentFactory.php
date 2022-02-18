<?php

namespace Database\Factories;

use App\Models\Story;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'story_id' => Story::all()->random(),
            'user_id' => User::all()->random(),
            'body' => $this->faker->sentence
        ];
    }
}
