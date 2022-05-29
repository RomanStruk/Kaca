<?php

namespace Kaca\Database\Factories;

use Kaca\Models\CashRegister;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashRegisterFactory extends Factory
{
    protected $model = CashRegister::class;

    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'title' => $this->faker->words(3, true),
            'address' => $this->faker->address(),
            'fiscal_number' => $this->faker->text(20),
            'licence_key' => $this->faker->text(20),
        ];
    }
}
