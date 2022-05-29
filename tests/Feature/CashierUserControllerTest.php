<?php

namespace Kaca\Tests\Feature;

use Kaca\Database\Factories\CashierFactory;
use Kaca\Database\Factories\CashRegisterFactory;
use Kaca\Database\Factories\UserFactory;
use Kaca\Kaca;
use Kaca\Models\CashRegister;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestModels\User;

class CashierUserControllerTest extends TestCase
{
    /** @test */
    public function it_index()
    {
        $this->actingAs($user = UserFactory::new()->create());

        $user1 = UserFactory::new()->withCashier()->create();
        $user2 = UserFactory::new()->withCashier()->create();

        $response = $this->get(route('kaca.cashier-users.index'));
        $response->assertOk();
        $response->assertSeeText($user1->{Kaca::$userFieldName});
        $response->assertSeeText($user2->{Kaca::$userFieldName});
        $response->assertDontSeeText($user->{Kaca::$userFieldName});
    }

    /** @test */
    public function it_create()
    {
        $this->actingAs(UserFactory::new()->create());

        $this->get(route('kaca.cashier-users.create'))
            ->assertOk();
    }

    /** @test */
    public function it_store()
    {
        $this->actingAs(UserFactory::new()->create());
        $cashier = CashierFactory::new()->create();
        $cashRegister = CashRegisterFactory::new()->create();
        $user = UserFactory::new()->create();


        $response = $this->post(route('kaca.cashier-users.store'), [
            'cashier_id' => $cashier->id,
            'cash_register_id' => $cashRegister->id,
            'user_id' => $user->id,
        ]);
        $response->assertRedirect(route('kaca.cashier-users.index'));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas(User::class, [
            'cashier_id' => $cashier->id,
            'cash_register_id' => $cashRegister->id,
            'id' => $user->id,
        ]);
    }

    /** @test */
    public function it_destroy()
    {
        $this->actingAs($user = UserFactory::new()->withCashier()->create());

        $this->assertNotNull($user->cashier_id);
        $this->assertNotNull($user->cash_register_id);
        $response = $this->delete(route('kaca.cashier-users.destroy', $user->id));
        $response->assertRedirect(route('kaca.cashier-users.index'));

        $user->refresh();
        $this->assertNull($user->cashier_id);
        $this->assertNull($user->cash_register_id);
    }

    /** @test */
    public function it_edit()
    {
        $this->actingAs(UserFactory::new()->create());
        $cashier = CashierFactory::new()->create();
        $cashRegister = CashRegisterFactory::new()->create();
        $user = UserFactory::new([
            'cashier_id' => $cashier->id,
            'cash_register_id' => $cashRegister->id,
        ])->create();


        $response = $this->get(route('kaca.cashier-users.edit', $user->id));
        $response->assertSeeText($cashier->full_name);
        $response->assertSee($cashier->id);
        $response->assertSeeText($cashRegister->title);
        $response->assertSee($cashRegister->id);
    }

    /** @test */
    public function it_update()
    {
        $this->actingAs(UserFactory::new()->create());
        $cashier = CashierFactory::new()->create();
        $cashRegister = CashRegisterFactory::new()->create();
        $cashier2 = CashierFactory::new()->create();
        $cashRegister2 = CashRegisterFactory::new()->create();
        $user = UserFactory::new([
            'cashier_id' => $cashier->id,
            'cash_register_id' => $cashRegister->id,
        ])->create();

        $this->assertDatabaseHas(User::class, [
            'id' => $user->id,
            'cashier_id' => $cashier->id,
            'cash_register_id' => $cashRegister->id,
        ]);

        $response = $this->post(route('kaca.cashier-users.update', $user->id), [
            'cashier_id' => $cashier2->id,
            'cash_register_id' => $cashRegister2->id,
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas(User::class, [
            'id' => $user->id,
            'cashier_id' => $cashier2->id,
            'cash_register_id' => $cashRegister2->id,
        ]);
    }
}