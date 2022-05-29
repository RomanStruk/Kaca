<?php

namespace Kaca\Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Kaca\Database\Factories\CashierFactory;
use Kaca\Database\Factories\UserFactory;
use Kaca\Models\Cashier;
use Kaca\Tests\TestResponses;
use Kaca\Tests\TestCase;

class CashierControllerTest extends TestCase
{
    /** @test */
    public function it_index()
    {
        $cashier = CashierFactory::new()->create();
        $cashier2 = CashierFactory::new()->create();

        $this->actingAs($user = UserFactory::new()->create());

        $response = $this->get(route('kaca.cashiers.index'));
        $response->assertOk();
        $response->assertSeeText($cashier->full_name);
        $response->assertSeeText('TEST');
        $response->assertSeeText($cashier2->full_name);
    }

    /** @test */
    public function it_show_create_form()
    {
        $this->actingAs($user = UserFactory::new()->create());

        $response = $this->get(route('kaca.cashiers.create'));
        $response->assertOk();
        $response->assertSee(route('kaca.cashiers.store'));
    }

    /** @test */
    public function it_destroy()
    {
        $this->actingAs($user = UserFactory::new()->create());
        $cashier = CashierFactory::new()->create();

        $this->assertDatabaseCount(Cashier::class, 1);

        $response = $this->delete(route('kaca.cashiers.destroy', $cashier));
        $response->assertRedirect(route('kaca.cashiers.index'));

        $this->assertDatabaseCount(Cashier::class, 0);
    }

    /** @test */
    public function it_sign_in_cashier_with_valid_credentials()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = UserFactory::new()->create());

        // підмінити відповідь для сервісу
        Http::fake([
            '*/api/v1/cashier/signin' => Http::response(TestResponses::$sign_in),
            '*/api/v1/cashier/me' => Http::response(TestResponses::$cashier_me),
            '*' => Http::response('', 200, []),
        ]);

        $cred = ['login' => 'user', 'password' => 'pass'];
        $response = $this->post(route('kaca.cashiers.store'), $cred);

        $this->assertDatabaseCount(Cashier::class, 1);
        $response->assertRedirect(route('kaca.cashiers.index'));
        $this->assertDatabaseHas(Cashier::class, ['access_token' => 'token_hash']);
        $this->assertDatabaseCount(Cashier::class, 1);
    }

    /** @test */
    public function it_failed_sign_in_with_invalid_credentials()
    {
        $this->actingAs($user = UserFactory::new()->create());
        Http::fake([
            '*/api/v1/cashier/signin' => Http::response(["message" => "Invalid credentials"], 403),
            '*/api/v1/cashier/me' => Http::response(TestResponses::$cashier_me),
            '*' => Http::response('', 200, []),
        ]);

        $cred = ['login' => 'user', 'password' => 'invalid_password'];
        $response = $this->post(route('kaca.cashiers.store'), $cred);

        $response->assertSessionHasErrors();
        $errors = session('errors');
        $this->assertEquals($errors->get('credentials')[0],"Invalid credentials");
    }

    /** @test */
    public function it_redirect_with_errors_after_failed_sing_in_request()
    {
        $this->actingAs($user = UserFactory::new()->create());
        Http::fake([
            '*/api/v1/cashier/signin' => Http::response(''),
            '*/api/v1/cashier/me' => Http::response(TestResponses::$cashier_me),
            '*' => Http::response('', 200, []),
        ]);

        $cred = ['login' => 'user', 'password' => 'password'];
        $response = $this->post(route('kaca.cashiers.store'), $cred);

        $response->assertSessionHasErrors();
    }
}
