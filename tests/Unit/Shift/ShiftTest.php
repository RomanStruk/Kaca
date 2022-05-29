<?php

namespace Kaca\Tests\Unit\Shift;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Kaca\Contracts\Shift\CloseShifts;
use Kaca\Contracts\Shift\OpenShifts;
use Kaca\Database\Factories\CashierFactory;
use Kaca\Database\Factories\CashRegisterFactory;
use Kaca\Database\Factories\ShiftFactory;
use Kaca\Database\Factories\UserFactory;
use Kaca\Exception\CheckboxExceptions;
use Kaca\Jobs\Shift\CreateShiftCheckbox;
use Kaca\Kaca;
use Kaca\Models\Cashier;
use Kaca\Models\Shift;
use Kaca\Synchronization;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestModels\User;
use Kaca\Tests\TestResponses;
use function now;

class ShiftTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo(now()->setDate(2022, 2, 5));
//        app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events();
    }

    /** @test */
    public function it_can_open_shift()
    {
        // авторизація касира з даними по касі і токену авторизації
        $cashRegister = CashRegisterFactory::new()->create();
        $cashier = CashierFactory::new()->create();
        $this->actingAs($user = User::factory(['cashier_id' => $cashier->id, 'cash_register_id' => $cashRegister->id])->create());

        // якщо попередня зміна закрита і в конфігах вказано що каса автоматично відкривається то метод верне істину
        $this->assertTrue(Gate::forUser($user)->allows('open', $cashier->shift));
    }

    /** @test */
    public function open_shift_dispatched_job()
    {
        $user = UserFactory::new()->withCashier()->create();
        $this->actingAs($user);

        Bus::fake();

        $this->assertDatabaseCount(Shift::class, 0);

        app(OpenShifts::class)->open($user, '1');

        Bus::assertDispatched(CreateShiftCheckbox::class);
    }

    /** @test */
    public function it_create_locally_shift_and_send_request()
    {
        $this->actingAs($user = User::factory()->withCashier()->create());
        // створити закриту зміну попереднього дня
        ShiftFactory::new()->forClosedStatus()->afterCreating(function ($sift) {
            \Kaca\Synchronization::finish($sift->id);
        })->create();

        // підмінити відповідь для сервісу
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


        app(OpenShifts::class)->open($user, Str::uuid()->toString());

        $openedShift = Kaca::findShiftByCashierUser($user);
        $this->assertDatabaseCount(Shift::class, 2);
        $this->assertEquals('CREATED', $openedShift->status);
        $this->assertEquals('4', $openedShift->serial);
        $this->assertNull($openedShift->opened_at);
        $this->assertNull($openedShift->closed_at);
        $this->assertTrue(Synchronization::STATUS_CREATED === Synchronization::getStatusFor($openedShift->id));
    }

    /** @test */
    public function it_auto_update_created_shift()
    {
        $cashRegister = CashRegisterFactory::new()->create();
        $cashier = CashierFactory::new()->create();
        $this->actingAs($user = User::factory(['cashier_id' => $cashier->id, 'cash_register_id' => $cashRegister->id])->create());

        ShiftFactory::new()->forClosedStatus()->afterCreating(function ($sift) {
            \Kaca\Synchronization::finish($sift->id);
        })->create(); // створити закриту зміну попереднього дня
        ShiftFactory::new(['id' => '8d4471ff-726c-4ec7-bfda-73f6a048c6a2', 'cashier_id' => $cashier->id])
            ->forCreatedStatus()
            ->create();
        // підмінити відповідь для сервісу
        Http::fake([
            '*/api/v1/shifts/*' => Http::response(TestResponses::$shift_status_opened),
            '*' => Http::response('', 200, []),
        ]);

        $this->artisan('kaca:process');

        $openedShift = Kaca::findShiftByCashierUser($user);
        $this->assertDatabaseCount(Shift::class, 2);
        $this->assertEquals('OPENED', $openedShift->status);
        $this->assertEquals('4', $openedShift->serial);
        $this->assertNotNull($openedShift->opened_at);
        $this->assertNull($openedShift->closed_at);
    }

    /** @test */
    public function it_sync_when_opened()
    {
        $shift = ShiftFactory::new()->forClosedStatus()->afterCreating(function ($shift) {
            \Kaca\Synchronization::finish($shift->id);
        })->create();
        $this->setUpOpenedShiftWithUser(['id' => '8d4471ff-726c-4ec7-bfda-73f6a048c6a2', 'cashier_id' => $shift->cashier_id, 'cash_register_id' => $shift->cash_register_id]);
        Http::fake([
            '/api/v1/cashier/shift' => Http::sequence()
                ->push(TestResponses::$shift_status_opened)
                ->push(TestResponses::$shift_status_closed),
            '*' => Http::response('', 200, []),
        ]);

        $this->assertEquals('OPENED', Kaca::findShiftByCashierUser(auth()->user())->status);

        $this->artisan('kaca:synchronizing');

        $this->assertDatabaseCount(Shift::class, 2);
        $this->assertEquals('OPENED', Kaca::findShiftByCashierUser(auth()->user())->status);
        $this->assertEquals('4', Kaca::findShiftByCashierUser(auth()->user())->serial);

        $this->artisan('kaca:synchronizing');

        $this->assertDatabaseCount(Shift::class, 2);
        $this->assertEquals('CLOSED', Kaca::findShiftByCashierUser(auth()->user())->status);
        $this->assertEquals('4', Kaca::findShiftByCashierUser(auth()->user())->serial);
        $this->assertTrue(Kaca::findShiftByCashierUser(auth()->user())->isClosed());
    }

    /** @test */
    public function it_update_shift_after_failed_request()
    {
        $cashRegister = CashRegisterFactory::new()->create();
        $cashier = CashierFactory::new()->create();
        $this->actingAs($user = User::factory(['cashier_id' => $cashier->id, 'cash_register_id' => $cashRegister->id])->create());

        Http::fake([
            '*' => Http::response('', 200, []),
        ]);

        $this->expectException(CheckboxExceptions::class);

        app(OpenShifts::class)->open($user, '111');
        $this->assertDatabaseCount(Shift::class, 0);
    }

    /** @test */
    public function it_open_shift()
    {
        $this->actingAs($user = User::factory()->withCashier()->create());
        Http::fake([
            '*/api/v1/shifts' => Http::response(TestResponses::$shift_status_created),
            '*/api/v1/shifts/*' => Http::response(TestResponses::$shift_status_opened),
            '*' => Http::response('', 200, []),
        ]);

        $this->assertDatabaseCount(Shift::class, 0);

        app(OpenShifts::class)->open($user, '8d4471ff-726c-4ec7-bfda-73f6a048c6a2');

        $this->assertDatabaseCount(Shift::class, 1);
        $this->assertDatabaseHas(Shift::class, ['status' => 'CREATED', 'opened_at' => null]);
        $this->assertEquals(Synchronization::STATUS_CREATED, Synchronization::getStatusFor(Kaca::findShiftByCashierUser($user)->id));

        $this->artisan('kaca:process');

        $this->assertDatabaseCount(Shift::class, 1);
        $this->assertDatabaseHas(Shift::class, ['status' => 'OPENED', 'opened_at' => '2022-02-05 06:00:04']);
        $this->assertEquals(Synchronization::STATUS_DONE, Synchronization::getStatusFor(Kaca::findShiftByCashierUser($user)->id));
    }

}
