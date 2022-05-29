<?php

namespace Kaca\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Kaca\Models\Report;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'serial' => $this->faker->randomDigitNotZero(),
            'is_z_report' => false,
            'shift_id' => ShiftFactory::new()->forOpenedStatus(),
            'created_at' => now(),
        ];
    }
}
