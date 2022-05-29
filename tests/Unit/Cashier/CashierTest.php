<?php

namespace Kaca\Tests\Unit\Cashier;

use Illuminate\Support\Facades\Http;
use Kaca\Actions\Cashier\UpdateOrCreateCashier;
use Kaca\Contracts\Cashier\SignInsCashiers;
use Kaca\Database\Factories\CashierFactory;
use Kaca\Database\Factories\CashRegisterFactory;
use Kaca\Database\Factories\UserFactory;
use Kaca\Kaca;
use Kaca\Models\Cashier;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestModels\User;
use Kaca\Tests\TestResponses;

class CashierTest extends TestCase
{
    public function test_user_can_appoint_as_cashier()
    {
        $cashier = CashierFactory::new()->create();
        $cashRegister = CashRegisterFactory::new()->create();
        $user = User::factory()->create();
        $this->assertNull($user->cashier_id);

        $user->cashier_id = $cashier->id;
        $user->cash_register_id = $cashRegister->id;
        $user->save();

        $this->assertNotNull($user->refresh()->cashier_id);
    }

    public function test_api_cashier_signin()
    {
        Http::fake([
            '*/api/v1/cashier/signin' => Http::response(TestResponses::$sign_in),
            '*' => Http::response(''),
        ]);

        // аторизація
        $accessToken = app(SignInsCashiers::class)->signIn(['login' => 'name', 'password' => 'pass']);

        $this->assertEquals('token_hash', $accessToken);
    }

    public function test_api_cashier_signin_and_return_empty_token()
    {
        $response = TestResponses::$sign_in;
        $response['access_token'] = null;
        Http::fake([
            '*/api/v1/cashier/signin' => Http::response($response),
            '*' => Http::response(''),
        ]);

        $this->expectExceptionMessage('Empty access token!');
        app(SignInsCashiers::class)->signIn(['login' => 'name', 'password' => 'pass']);
    }

    /** @test */
    public function it_create_locally_cashier_after_success_auth()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = UserFactory::new()->create());

        Http::fake([
            '*/api/v1/cashier/me' => Http::response(TestResponses::$cashier_me),
            '*' => Http::response(''),
        ]);

        $cashier = app(UpdateOrCreateCashier::class)->handle('token_hash', $user);

        $this->assertEquals('d8acda9b-f93e-4865-xxxx-3ece40daaf0b', $cashier->id);
        $this->assertEquals('cashier full name', $cashier->full_name);
        $this->assertEquals('000000000', $cashier->nin);
        $this->assertEquals('test_key_id', $cashier->key_id);
        $this->assertEquals('TEST', $cashier->signature_type);
        $this->assertEquals('token_hash', $cashier->access_token);
    }

    /** @test */
    public function it_update_cashier_data_if_that_exist()
    {
        $localCashier = CashierFactory::new(['id' => 'd8acda9b-f93e-4865-xxxx-3ece40daaf0b'])->create();
        $user = UserFactory::new(['cashier_id' => $localCashier->id])->create();

        Http::fake([
            '*/api/v1/cashier/me' => Http::response(TestResponses::$cashier_me),
            '*' => Http::response(''),
        ]);

        $this->assertDatabaseHas(Cashier::class, ['full_name' => $localCashier->full_name]);
        $cashier = app(UpdateOrCreateCashier::class)->handle('token_hash', $user);

        $this->assertEquals('d8acda9b-f93e-4865-xxxx-3ece40daaf0b', $cashier->id);
        $this->assertEquals('cashier full name', $cashier->full_name);
    }
}
