<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\odel=Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => $this->faker->word,
            'description' => $this->faker->address,
            'network'     => Device::NETWORK_WIFI,
            'type'        => Device::MULTI_PURPOSE_DEVICE,
            'token'       => Str::random(60),
        ];
    }
}
