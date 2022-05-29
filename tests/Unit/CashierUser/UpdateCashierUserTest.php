<?php

namespace Kaca\Tests\Unit\CashierUser;

use Kaca\Actions\CashierUser\UpdateCashierUser;
use Kaca\Database\Factories\CashierFactory;
use Kaca\Database\Factories\CashRegisterFactory;
use Kaca\Database\Factories\UserFactory;
use Kaca\Tests\TestCase;

class UpdateCashierUserTest extends TestCase
{
    /** @test */
    public function it_update_cashier_and_cash_register_for_user()
    {
        $user = UserFactory::new()->withCashier()->create();
        $cashier = CashierFactory::new()->create();
        $cashRegister = CashRegisterFactory::new()->create();

        $this->assertNotEquals($user->cashier_id, $cashier->id);
        $this->assertNotEquals($user->cash_register_id, $cashRegister->id);

        app(UpdateCashierUser::class)->update($user, [
            'cashier_id' => $cashier->id,
            'cash_register_id' => $cashRegister->id,
        ]);

        $this->assertEquals($user->cashier_id, $cashier->id);
        $this->assertEquals($user->cash_register_id, $cashRegister->id);
    }
}