<?php

namespace Database\Factories;

use App\Enums\BrazilianState;
use App\Models\Associate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Associate>
 */
class AssociateFactory extends Factory
{
    protected $model = Associate::class;

    public function definition(): array
    {
        $fakerBR = \Faker\Factory::create('pt_BR');

        return [
            'name' => $fakerBR->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'cpf' => $fakerBR->cpf(false),
            'telephone' => preg_replace('/[^0-9]/', '', $fakerBR->cellphoneNumber()),
            'city' => $fakerBR->city(),
            'state' => $this->faker->randomElement(BrazilianState::cases()),
        ];
    }
}
