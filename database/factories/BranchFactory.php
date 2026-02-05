<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => $this->faker->city() . ' Branch',
            'code' => strtoupper($this->faker->bothify('BR-###')),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'website' => $this->faker->url(),
            'status' => true,
            'location' => $this->faker->city(),
        ];
    }
}
