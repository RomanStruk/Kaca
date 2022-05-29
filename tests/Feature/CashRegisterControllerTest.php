<?php

namespace Kaca\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Kaca\Database\Factories\CashRegisterFactory;
use Kaca\Database\Factories\UserFactory;
use Kaca\Models\CashRegister;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestResponses;

class CashRegisterControllerTest extends TestCase
{
    /** @test */
    public function it_index()
    {
        $this->actingAs($user = UserFactory::new()->create());
        $cashRegister = CashRegisterFactory::new()->create();
        $cashRegister2 = CashRegisterFactory::new()->create();

        $response = $this->get(route('kaca.cash-registers.index'));
        $response->assertOk();
        $response->assertSeeText($cashRegister->title);
        $response->assertSeeText($cashRegister2->title);
    }

    /** @test */
    public function it_create()
    {
        $this->actingAs($user = UserFactory::new()->create());

        $response = $this->get(route('kaca.cash-registers.create'));
        $response->assertOk();
    }

    /** @test */
    public function it_store_when_valid_license_key()
    {
        $this->actingAs($user = UserFactory::new()->create());
        Http::fake([
            '*/api/v1/cash-registers/info' => Http::response(TestResponses::$cash_register_info),
            '*' => Http::response('', 200, []),
        ]);

        $response = $this->post(route('kaca.cash-registers.store'), [
            'licence_key' => 'valid_licence_key'
        ]);
        $response->assertRedirect(route('kaca.cash-registers.index'));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas(CashRegister::class, [
            'licence_key' => 'valid_licence_key'
        ]);
    }

    /** @test */
    public function it_cant_store_when_invalid_license_key()
    {
        $this->actingAs($user = UserFactory::new()->create());
        Http::fake([
            '*/api/v1/cash-registers/info' => Http::response(['message' => 'Invalid licence key'], 422),
            '*' => Http::response('', 200, []),
        ]);

        $response = $this->post(route('kaca.cash-registers.store'), [
            'licence_key' => 'invalid_licence_key'
        ]);
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing(CashRegister::class, [
            'licence_key' => 'invalid_licence_key'
        ]);
    }

    /** @test */
    public function it_cant_store_when_failed_request()
    {
        $this->actingAs($user = UserFactory::new()->create());
        Http::fake([
            '*/api/v1/cash-registers/info' => Http::response(''),
            '*' => Http::response('', 200, []),
        ]);

        $response = $this->post(route('kaca.cash-registers.store'), [
            'licence_key' => 'valid_licence_key'
        ]);
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing(CashRegister::class, [
            'licence_key' => 'valid_licence_key'
        ]);
    }

    /** @test */
    public function it_destroy()
    {
        $this->actingAs($user = UserFactory::new()->create());
        $cashRegister = CashRegisterFactory::new()->create();

        $this->assertDatabaseCount(CashRegister::class, 1);

        $this->delete(route('kaca.cash-registers.destroy', $cashRegister));

        $this->assertDatabaseCount(CashRegister::class, 0);
    }
}