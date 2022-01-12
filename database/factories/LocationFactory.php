<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
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

            "lat" => $this->faker->latitude(
                $min = ($lang * 10000 - rand(0, 50)) / 10000,
                $max = ($lang * 10000 + rand(0, 50)) / 10000
            ),
            "lng" => $this->faker->longitude(
                $min = ($long * 10000 - rand(0, 50)) / 10000,
                $max = ($long * 10000 + rand(0, 50)) / 10000
            ),

        ];
    }
}
