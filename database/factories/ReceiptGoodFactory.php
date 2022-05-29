<?php

namespace Kaca\Database\Factories;

use Kaca\Models\ReceiptGood;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceiptGoodFactory extends Factory
{
    protected $model = ReceiptGood::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'code' => $this->faker->randomNumber(),
            'price' => $this->faker->numberBetween(100, 1000),
            'quantity' => $this->faker->numberBetween(1, 5),
            'is_return' => false,
        ];
    }

    public function forRefund()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_return' => true,
            ];
        });
    }
}
