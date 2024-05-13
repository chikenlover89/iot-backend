<?php

namespace Database\Factories;

use App\Models\Peripheral;
use App\Models\PeripheralAlert;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PeripheralValueAlert>
 */
class PeripheralAlertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'          => $this->faker->word,
            'description'   => $this->faker->words,
            'alert_value'   => 90.00,
            'resolve_value' => 89.00,
            'direction'     => PeripheralAlert::DIRECTION_ASCENDING,
        ];
    }
}
