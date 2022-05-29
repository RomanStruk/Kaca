<?php

namespace Kaca\Tests\Unit\CashRegister;

use Illuminate\Support\Facades\Http;
use Kaca\ActionRecorder;
use Kaca\Actions\CashRegister\SyncCashRegister;
use Kaca\Database\Factories\CashRegisterFactory;
use Kaca\Database\Factories\UserFactory;
use Kaca\Models\CashRegister;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestResponses;

class SyncCashRegisterTest extends TestCase
{
    /** @test */
    public function it_store_cash_register_with_valid_license_key()
    {
        $user = UserFactory::new()->create();

        Http::fake([
            '*/api/v1/cash-registers/info' => Http::response(TestResponses::$cash_register_info),
            '*' => Http::response('', 200, []),
        ]);

        $this->assertDatabaseCount(CashRegister::class, 0);

        app(SyncCashRegister::class)->sync($user, 'license_key');

        $this->assertDatabaseHas(CashRegister::class, ['id' => TestResponses::$cash_register_info['id']]);

        $creator =  app(ActionRecorder::class)->findUserForAction(CashRegister::class, ActionRecorder::CREATE);
        $this->assertEquals($user->name, $creator->getCreatorName());
    }

    /** @test */
    public function it_update_cash_register_with_valid_license_key()
    {
        $user = UserFactory::new()->create();
        $cashRegister = CashRegisterFactory::new(['licence_key' => 'test_key'])->create();

        Http::fake([
            '*/api/v1/cash-registers/info' => Http::response(TestResponses::$cash_register_info),
            '*' => Http::response('', 200, []),
        ]);

        $this->assertDatabaseCount(CashRegister::class, 1);
        $this->assertDatabaseMissing(CashRegister::class,['title' => TestResponses::$cash_register_info['title']]);

        app(SyncCashRegister::class)->sync($user, 'test_key');

        $this->assertDatabaseCount(CashRegister::class, 1);
        $this->assertDatabaseHas(CashRegister::class, [
            'title' => TestResponses::$cash_register_info['title'],
            'id' => TestResponses::$cash_register_info['id'],
        ]);
        $this->assertDatabaseMissing(CashRegister::class,['id' => $cashRegister->id]);

        $creator =  app(ActionRecorder::class)->findUserForAction(CashRegister::class, ActionRecorder::UPDATE);
        $this->assertEquals($user->name, $creator->getCreatorName());
    }
}