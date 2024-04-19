<?php

namespace Database\Factories;

use App\Models\Peripheral;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peripheral>
 */
class PeripheralFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "function"     => Peripheral::FUNCTION_INPUT,
            "type"         => Peripheral::TYPE_TEMPERATURE,
            "parameter_id" => Peripheral::PREFIX_TEMPERATURE . '_' . $this->faker->numberBetween(1, 100),
            'hidden'       => false,
        ];
    }
}
