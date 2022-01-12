<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $lang = 22.33716406641762;
        $long = 91.78867267289668;
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            "lat" => $this->faker->latitude(
                $min = ($lang * 10000 - rand(0, 50)) / 10000,
                $max = ($lang * 10000 + rand(0, 50)) / 10000
            ),
            "lng" => $this->faker->longitude(
                $min = ($long * 10000 - rand(0, 50)) / 10000,
                $max = ($long * 10000 + rand(0, 50)) / 10000
            ),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
