<?php

namespace Kaca\Tests\Unit\CashierUser;

use Kaca\Actions\CashierUser\DeleteCashierUser;
use Kaca\Database\Factories\UserFactory;
use Kaca\Tests\TestCase;

class DeleteCashierUserTest extends TestCase
{
    /** @test */
    public function it_delete_cashier_user()
    {
        $user = UserFactory::new()->withCashier()->create();

        $this->assertNotNull($user->cashier_id);
        $this->assertNotNull($user->cash_register_id);

        app(DeleteCashierUser::class)->delete($user);

        $user->refresh();
        $this->assertNull($user->cashier_id);
        $this->assertNull($user->cash_register_id);
    }
}