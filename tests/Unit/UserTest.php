<?php

namespace Kaca\Tests\Unit;

use Kaca\Kaca;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestModels\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /** @test */
    public function it_has_cashier()
    {
        $this->actingAs($user = User::factory()->withCashier()->create());

        $this->assertNotNull(Kaca::findCashierByCashierUser($user));
    }

    /** @test */
    public function it_has_cash_register()
    {
        $this->actingAs($user = User::factory()->withCashier()->create());

        $this->assertNotNull(Kaca::findCashRegisterByCashierUser($user));
    }
}
