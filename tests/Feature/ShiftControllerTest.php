<?php

namespace Kaca\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Kaca\Database\Factories\CashierFactory;
use Kaca\Database\Factories\CashRegisterFactory;
use Kaca\Database\Factories\ShiftFactory;
use Kaca\Kaca;
use Kaca\Models\Shift;
use Kaca\Models\Synchronization;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestModels\User;
use Kaca\Tests\TestResponses;

class ShiftControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo(now()->setDate(2022, 2, 5));
    }

    /** @test */
    public function cashier_can_open_shift()
    {
        $this->withoutExceptionHandling();

        $cashRegister = CashRegisterFactory::new()->create();
        $cashier = CashierFactory::new()->create();
        $this->actingAs($user = User::factory(['cashier_id' => $cashier->id, 'cash_register_id' => $cashRegister->id])->create());

        ShiftFactory::new(['cashier_id' => $cashier->id, 'cash_register_id' => $cashRegister->id])
            ->forClosedStatus()
            ->afterCreating(function ($sift) {
                \Kaca\Synchronization::finish($sift->id);
            })->create();

        Http::fake([
            '*/api/v1/shifts' => function (\Illuminate\Http\Client\Request $request) {
                $response = TestResponses::$shift_status_created;
                $response['id'] = $request->offsetGet('id');
                return Http::response($response);
            },
            '*/api/v1/shifts/*' => function (\Illuminate\Http\Client\Request $request) {
                $url = explode('/', $request->url());
                $response = TestResponses::$shift_status_opened;
                $response['id'] = end($url);
                return Http::response($response);
            },
            '*' => Http::response('', 200, []),
        ]);

        $this->assertDatabaseCount(Shift::class, 1);
        $this->assertEquals('CLOSED', Shift::first()->status);

        $this->post(route('kaca.shifts.store'));

        $this->assertDatabaseCount(Shift::class, 2);
        $this->assertEquals('CREATED', Kaca::findShiftByCashierUser($user)->status);
        $this->assertEquals('4', Kaca::findShiftByCashierUser($user)->serial);
//        dd(Synchronization::all()->toArray());
        // синхронізація статусу
        $this->artisan('kaca:process');

        $this->assertEquals('OPENED', Kaca::findShiftByCashierUser($user)->status);
        $this->assertEquals('4', Kaca::findShiftByCashierUser($user)->serial);
    }

    /** @test */
    public function it_index()
    {
        $shift = ShiftFactory::new(['serial' => 99])
            ->forClosedStatus()
            ->afterCreating(function ($sift) {
                \Kaca\Synchronization::finish($sift->id);
            })->create();
        $this->actingAs($user = User::factory([
            'cashier_id' => $shift->cashier->id,
            'cash_register_id' => $shift->cashRegister->id,
        ])->create());

        $response = $this->get(route('kaca.shifts.index'));
        $response->assertOk();
        $response->assertSeeText($shift->serial);
        $response->assertSeeText($shift->cashRegister->title);
        $response->assertSeeText($shift->cashier->full_name);
        $response->assertSeeText($shift->opened_at->format(config('kaca.date_format')));
        $response->assertSeeText($shift->closed_at->format(config('kaca.date_format')));
    }

    /** @test */
    public function it_close_shift()
    {
        $shift = $this->setUpOpenedShiftWithUser();
        $closed = TestResponses::$shift_status_closed;
        $closed['id'] = $shift->id;
        Http::fake([
            '*/api/v1/shifts/*' => Http::sequence()
                ->push($closed),
            '*' => Http::response('', 200, []),
        ]);

        $this->assertDatabaseHas(Shift::class, [
            'id' => $shift->id,
            'status' => 'OPENED',
        ]);

        $this->delete(route('kaca.shifts.destroy', $shift));

        $this->assertDatabaseHas(Shift::class, [
            'id' => $shift->id,
            'status' => 'CLOSED',
        ]);
    }

    /** @test */
    public function it_throw_error_message_after_failed_closing_shift()
    {
        $shift = $this->setUpOpenedShiftWithUser();
        Http::fake([
            '*/api/v1/shifts/*' => Http::sequence()
                ->push(''),
            '*' => Http::response('', 200, []),
        ]);

        $this->assertDatabaseHas(Shift::class, ['id' => $shift->id, 'status' => 'OPENED',]);

        $response = $this->delete(route('kaca.shifts.destroy', $shift));

        $response->assertSessionHasErrors();
        $this->assertDatabaseHas(Shift::class, ['id' => $shift->id, 'status' => 'OPENED',]);
    }

    /** @test */
    public function it_throw_error_message_after_failed_opening_shift()
    {
        $cashRegister = CashRegisterFactory::new()->create();
        $cashier = CashierFactory::new()->create();
        $this->actingAs($user = User::factory(['cashier_id' => $cashier->id, 'cash_register_id' => $cashRegister->id])->create());

        ShiftFactory::new(['cashier_id' => $cashier->id, 'cash_register_id' => $cashRegister->id])
            ->forClosedStatus()
            ->afterCreating(function ($sift) {
                \Kaca\Synchronization::finish($sift->id);
            })->create();

        Http::fake([
            '*/api/v1/shifts' => function (\Illuminate\Http\Client\Request $request) {
                return Http::response('');
            },
            '*' => Http::response('', 200, []),
        ]);

        $response = $this->post(route('kaca.shifts.store'));
        $response->assertSessionHasErrors();
    }
}
