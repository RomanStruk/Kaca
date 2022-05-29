<?php

namespace Kaca\Tests\Unit\Report;

use Kaca\Actions\Report\StoreLocalReport;
use Kaca\Database\Factories\ShiftFactory;
use Kaca\Models\Report;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestResponses;

class StoreLocalReportTest extends TestCase
{
    /** @test */
    public function it_store_local_report_from_response_array()
    {
        $shift = ShiftFactory::new()->forOpenedStatus(now()->setTime(8, 0))->create();

        app(StoreLocalReport::class)->store($shift, TestResponses::$x_report);

        $this->assertDatabaseHas(Report::class, [
            'id' => TestResponses::$x_report['id'],
            'serial' => TestResponses::$x_report['serial'],
            'is_z_report' => TestResponses::$x_report['is_z_report'],
            'shift_id' => $shift->id,
        ]);
    }
}