<?php

namespace Kaca\Tests\Unit\CashRegister;

use Kaca\Actions\CashRegister\DeleteLocalCashRegister;
use Kaca\Database\Factories\CashRegisterFactory;
use Kaca\Database\Factories\UserFactory;
use Kaca\Models\Action;
use Kaca\Models\CashRegister;
use Kaca\Tests\TestCase;

class DeleteLocalCashRegisterTest extends TestCase
{
    /** @test */
    public function it_delete_local_cash_register_and_recorded_action()
    {
        $user = UserFactory::new()->create();
        $cashRegister = CashRegisterFactory::new()->create();

        $this->assertDatabaseCount(CashRegister::class, 1);

        app(DeleteLocalCashRegister::class)->delete($user, $cashRegister);

        $this->assertDatabaseCount(CashRegister::class, 0);
        $this->assertDatabaseHas(Action::class, [
            'target' => $cashRegister->id,
            'user_id' => $user->id,
        ]);
    }
}