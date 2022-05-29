<?php

namespace Kaca\Tests\Unit\Report;

use Illuminate\Support\Facades\Http;
use Kaca\Actions\Report\CreateReport;
use Kaca\Database\Factories\ShiftFactory;
use Kaca\Database\Factories\UserFactory;
use Kaca\Exception\CheckboxExceptions;
use Kaca\Exception\CheckboxValidationException;
use Kaca\Kaca;
use Kaca\Models\Report;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestResponses;

class CreateReportTest extends TestCase
{
    /** @test */
    public function it_create_report()
    {
        $shift = ShiftFactory::new()->forOpenedStatus(now()->setTime(8, 0))->create();
        $user = UserFactory::new(['cashier_id' => $shift->cashier_id, 'cash_register_id' => $shift->cash_register_id])->create();
        $this->actingAs($user);

        Http::fake([
            '*/api/v1/reports' => Http::response(TestResponses::$x_report),
            '*' => Http::response('', 200, []),
        ]);

        $report = app(CreateReport::class)->create(Kaca::findCashierByCashierUser($user));

        $this->assertEquals(TestResponses::$x_report['id'], $report->id);
        $this->assertEquals(TestResponses::$x_report['serial'], $report->serial);
        $this->assertEquals(TestResponses::$x_report['is_z_report'], $report->is_z_report);

        $this->assertDatabaseHas(Report::class, [
            'id' => TestResponses::$x_report['id']
        ]);
    }

    /** @test */
    public function it_throw_exception_after_failed_response()
    {
        $shift = ShiftFactory::new()->forOpenedStatus(now()->setTime(8, 0))->create();
        $user = UserFactory::new(['cashier_id' => $shift->cashier_id, 'cash_register_id' => $shift->cash_register_id])->create();
        $this->actingAs($user);

        Http::fake([
            '*/api/v1/reports' => Http::sequence()
                ->push('')
                ->push(["message" => "Validation error"], 422),
            '*' => Http::response('', 200, []),
        ]);

        $this->expectException(CheckboxExceptions::class);

        app(CreateReport::class)->create(Kaca::findCashierByCashierUser($user));

        $this->expectException(CheckboxValidationException::class);

        app(CreateReport::class)->create(Kaca::findCashierByCashierUser($user));
    }
}