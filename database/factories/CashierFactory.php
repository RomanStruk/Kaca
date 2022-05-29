<?php

namespace Kaca\Database\Factories;

use Kaca\Models\Cashier;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashierFactory extends Factory
{
    protected $model = Cashier::class;

    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'full_name' => '',
            'nin' => '',
            'key_id' => '',
            'signature_type' => 'TEST',
            'access_token' => 'token',
            'certificate_end' => now()->addMonth(),
        ];
    }
}
