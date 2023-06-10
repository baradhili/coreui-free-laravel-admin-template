<?php

namespace Database\Factories;

use App\Models\Status as Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'class' => $this->faker->word(),
        ];
    }
}
