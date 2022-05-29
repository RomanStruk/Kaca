<?php

namespace Kaca\Database\Factories;

use Kaca\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftFactory extends Factory
{
    protected $model = Shift::class;

    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'serial' => '1',
            'status' => 'OPENED',
            'cashier_id' => CashierFactory::new(),
            'cash_register_id' => CashRegisterFactory::new(),
            'created_at' => now(),
        ];
    }

    public function forCreatedStatus()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'CREATED',
                'created_at' => now()->setDate(2022,2, 5)->setTime(8,0),
                'opened_at' => null,
                'closed_at' => null
            ];
        });
    }

    public function forOpenedStatus($opened_at = null)
    {
        return $this->state(function (array $attributes) use ($opened_at) {
            return [
                'status' => 'OPENED',
                'created_at' => $opened_at ?? now()->setDate(2022,2, 5)->setTime(8,0),
                'opened_at' => $opened_at ?? now()->setDate(2022,2, 5)->setTime(8,0),
                'updated_at' => $opened_at ?? now()->setDate(2022,2, 5)->setTime(8,0),
                'closed_at' => null,
            ];
        });
    }

    public function forClosedStatus()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'CLOSED',
                'serial' => '3',
                'opened_at' => now()->setDate(2022,2, 4)->setTime(8,0),
                'closed_at' => now()->setDate(2022,2, 4)->setTime(21,55),
                'created_at' => now()->setDate(2022,2, 4)->setTime(8,0),
            ];
        });
    }
}
