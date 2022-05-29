<?php

namespace Kaca\Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Kaca\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function auth;
use function route;

class PolicyTest extends TestCase
{
    /** @test */
    function guests_can_not_create_posts()
    {
        // We're starting from an unauthenticated state
        $this->assertFalse(auth()->check());

//        $this->withoutExceptionHandling();

        $this->post(route('kaca.cashier.cash-register.update'), [
            'title' => 'A valid title',
            'body'  => 'A valid body',
        ], ['Accept'=>'application/json'])->assertUnauthorized();
    }

}
