<?php

namespace Kaca\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Kaca\Models\ReceiptPayment;

class ReceiptPaymentFactory extends Factory
{
    protected $model = ReceiptPayment::class;

    public function definition(): array
    {
        return [
            'value' => $this->faker->randomDigitNotZero() * 100,
            'type' => 'CASHLESS',
            'label' => 'Картка',
        ];
    }
}
