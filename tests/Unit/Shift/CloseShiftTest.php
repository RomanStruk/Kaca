<?php

namespace Kaca\Tests\Unit\Shift;

use Illuminate\Support\Facades\Http;
use Kaca\Contracts\Shift\CloseShifts;
use Kaca\Exception\CheckboxExceptions;
use Kaca\Models\Shift;
use Kaca\Synchronization;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestResponses;

class CloseShiftTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo(now()->setDate(2022, 2, 5));
    }

    /** @test */
    public function it_catch_error_when_failed_request()
    {
        $shift = $this->setUpOpenedShiftWithUser(['id' => TestResponses::$shift_status_closing['id']]);
        Http::fake([
            '*/api/v1/shifts/*' => Http::sequence()
                ->push(TestResponses::$shift_status_closing)
                ->push(''),
            '*' => Http::response('', 200, []),
        ]);

        $this->assertDatabaseCount(Shift::class, 1);
        $this->assertDatabaseHas(Shift::class, ['status' => 'OPENED', 'opened_at' => '2022-02-05 08:00:00']);

        app(CloseShifts::class)->close(auth()->user(), $shift);

        $this->assertDatabaseHas(Shift::class, ['status' => 'CLOSING', 'closed_at' => null]);

        try {
            $this->artisan('kaca:process');
        }catch (\Throwable $throwable){
            $this->assertInstanceOf(\Kaca\Contracts\CheckboxExceptions::class, $throwable);
        }

        $this->assertDatabaseCount(Shift::class, 1);
        $this->assertDatabaseHas(Shift::class, ['status' => 'CLOSING', 'closed_at' => null]);
        $this->assertEquals(Synchronization::STATUS_FAILED, Synchronization::getStatusFor($shift->id));
    }


    /** @test */
    public function it_close_shift()
    {
        $shift = $this->setUpOpenedShiftWithUser();
        Http::fake([
            '*/api/v1/shifts/*' => Http::sequence()
                ->push(TestResponses::$shift_status_closing),
            '/api/v1/cashier/shift' => Http::sequence()
                ->push(TestResponses::$shift_status_closed),
            '*' => Http::response('', 200, []),
        ]);

        $this->assertDatabaseCount(Shift::class, 1);
        $this->assertDatabaseHas(Shift::class, ['status' => 'OPENED', 'opened_at' => '2022-02-05 08:00:00']);

        app(CloseShifts::class)->close(auth()->user(), $shift);

        $this->assertDatabaseHas(Shift::class, ['status' => 'CLOSING', 'closed_at' => null]);

        $this->artisan('kaca:synchronizing');

        $this->assertDatabaseCount(Shift::class, 1);
        $this->assertDatabaseHas(Shift::class, ['status' => 'CLOSED', 'closed_at' => '2022-02-05 19:50:02']);
    }
}