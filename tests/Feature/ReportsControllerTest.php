<?php

namespace Kaca\Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Http;
use Kaca\Contracts\Shift\CloseShifts;
use Kaca\Database\Factories\ReportFactory;
use Kaca\Database\Factories\ShiftFactory;
use Kaca\Database\Factories\UserFactory;
use Kaca\Models\Report;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestResponses;

class ReportsControllerTest extends TestCase
{
    /** @test */
    public function it_show()
    {
        $this->withoutExceptionHandling();
        $user = UserFactory::new()->withCashier()->create();
        $this->actingAs($user);
        $report = ReportFactory::new(['serial' => 1])->create();

        Http::fake([
            '*/api/v1/reports/*' => Http::response(TestResponses::$x_report_as_text, 200, ['Content-Type' => 'text/plain; charset=utf-8']),
            '*' => Http::response('', 200, []),
        ]);

        $response = $this->get(route('kaca.reports.show', $report));
        $response->assertSee('№' . $report->serial);
        $response->assertSee('ТЕСТОВИЙ ЗВІТ');
    }

    /** @test */
    public function it_store()
    {
        $shift = ShiftFactory::new()->forOpenedStatus(now()->setTime(8, 0))->create();
        $user = UserFactory::new(['cashier_id' => $shift->cashier_id, 'cash_register_id' => $shift->cash_register_id])->create();
        $this->actingAs($user);

        Http::fake([
            '*/api/v1/reports' => Http::response(TestResponses::$x_report),
            '*' => Http::response('', 200, []),
        ]);

        $response = $this->post(route('kaca.reports.store'));
        $response->assertRedirect(route('kaca.reports.show', TestResponses::$x_report['id']));
        $this->assertDatabaseHas(Report::class, ['id' => TestResponses::$x_report['id']]);
    }

    /** @test */
    public function it_index()
    {
        $user = UserFactory::new()->withCashier()->create();
        $this->actingAs($user);
        $report = ReportFactory::new(['serial' => 693])->create();
        $report2 = ReportFactory::new(['serial' => 789])->create();

        $response = $this->get(route('kaca.reports.index'));
        $response->assertSee($report->serial);
        $response->assertSee($report2->serial);
    }

    /** @test */
    public function it_store_z_report_after_close_shift()
    {
        $shift = $this->setUpOpenedShiftWithUser();
        Http::fake([
            '*/api/v1/shifts/*' => Http::sequence()
                ->push(TestResponses::$shift_status_closed),
            '*' => Http::response('', 200, []),
        ]);

        app(CloseShifts::class)->close(auth()->user(), $shift);

        $this->assertDatabaseHas(Report::class, [
            'id' => TestResponses::$shift_status_closed['z_report']['id'],
            'is_z_report' => true,
        ]);
    }

    /** @test */
    public function it_redirect_with_error_after_failed_request()
    {
        $shift = ShiftFactory::new()->forOpenedStatus(now()->setTime(8, 0))->create();
        $user = UserFactory::new(['cashier_id' => $shift->cashier_id, 'cash_register_id' => $shift->cash_register_id])->create();
        $this->actingAs($user);

        Http::fake([
            '*/api/v1/reports' => Http::response([
                "detail" => [["loc" => ["string"], "msg" => "string", "type" => "string"]],
                "message" => "Validation Error"], 422),
            '*' => Http::response('', 200, []),
        ]);

        $response = $this->post(route('kaca.reports.store'));

        $response->assertSessionHasErrors();
        $errors = session('errors');
        $this->assertEquals($errors->get(0)[0],"Validation Error");
    }
}
