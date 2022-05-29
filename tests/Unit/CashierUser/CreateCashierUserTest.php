<?php

namespace Kaca\Tests\Unit\CashierUser;

use Kaca\Actions\CashierUser\CreateCashierUser;
use Kaca\Database\Factories\CashierFactory;
use Kaca\Database\Factories\CashRegisterFactory;
use Kaca\Database\Factories\UserFactory;
use Kaca\Kaca;
use Kaca\Tests\TestCase;

class CreateCashierUserTest extends TestCase
{
    /** @test */
    public function it_create_user_with_cashier_data()
    {
        $user = UserFactory::new()->create();
        $cashier = CashierFactory::new()->create();
        $cashRegister = CashRegisterFactory::new()->create();

        $this->assertNull($user->cashier_id);
        $this->assertNull($user->cash_register_id);

        $updatedUser = app(CreateCashierUser::class)->create([
            'user_id' => $user->id,
            'cashier_id' => $cashier->id,
            'cash_register_id' => $cashRegister->id,
        ]);

        $this->assertEquals($updatedUser->cashier_id, $cashier->id);
        $this->assertEquals($updatedUser->cash_register_id, $cashRegister->id);
        $this->assertEquals($cashier->toArray(), Kaca::findCashierByCashierUser($updatedUser)->toArray());
    }
}