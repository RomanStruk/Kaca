<?php

namespace Kaca\Tests\Unit\Cashier;

use Kaca\Actions\Cashier\DeleteLocalCashier;
use Kaca\Database\Factories\UserFactory;
use Kaca\Kaca;
use Kaca\Models\Action;
use Kaca\Models\Cashier;
use Kaca\Tests\TestCase;

class DeleteLocalCashierTest extends TestCase
{
    /** @test */
    public function it_delete_local_cashier_data()
    {
        $this->actingAs($user = UserFactory::new()->withCashier()->create());

        $this->assertDatabaseCount(Cashier::class, 1);

        app(DeleteLocalCashier::class)->delete($user, $cashier = Kaca::findCashierByCashierUser($user));

        $this->assertDatabaseCount(Cashier::class, 0);

        $this->assertDatabaseHas(Action::class, [
            'target' => $cashier->id,
            'user_id' => $user->id,
        ]);
    }
}