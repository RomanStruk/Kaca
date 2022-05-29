<?php

namespace Kaca\Database\Factories;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Kaca\Models\Receipt;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceiptFactory extends Factory
{
    protected $model = Receipt::class;

    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'type' => 'SELL',
            'fiscal_code' => null,
            'serial' => null,
            'status' => 'CREATED',
            'delivery' => [
                'emails' => [
                    $this->faker->email()
                ]
            ],
            'related_receipt_id' => null,
            'shift_id' => ShiftFactory::new()->forOpenedStatus(),
            'order_id' => null,
            'reverse_compatibility_data' => '',
        ];
    }

}
