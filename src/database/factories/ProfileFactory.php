<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'image' => 'profile.jpg',
            'postal_code' => '123-4567',
            'address' => $this->faker->address(),
            'building' => 'テストビル101',
        ];
    }
}
