<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Alert;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alert>
 */
class AlertFactory extends Factory
{
    protected $account_id;

    public function withAccount(Account $account): self
    {
        $this->account_id = $account->id;

        return $this;
    }

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
            'account_id'  => $this->account_id ?? Account::inRandomOrder()->first()->id,
            'type'        => Alert::ALERT_TYPE_SCHEDULED,
        ];
    }
}
