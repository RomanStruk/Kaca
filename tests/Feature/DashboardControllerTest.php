<?php

namespace Kaca\Tests\Feature;

use Kaca\ActionRecorder;
use Kaca\Database\Factories\ReceiptFactory;
use Kaca\Database\Factories\UserFactory;
use Kaca\Kaca;
use Kaca\Models\Receipt;
use Kaca\Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    /** @test */
    public function it_index()
    {
        $this->travelTo(now()->setDate(2022, 2, 5));
        $receipt = ReceiptFactory::new()->create();
        $this->actingAs($user = UserFactory::new([
            'cashier_id' => $receipt->shift->cashier_id,
            'cash_register_id' => $receipt->shift->cash_register_id,
        ])->create());
        ActionRecorder::creating($user, Receipt::class, $receipt->id);

        $response = $this->get(route('kaca.index'));
        $response->assertOk();
        $response->assertSeeText($receipt->fiscal_code);
        $response->assertSeeText($user->{Kaca::$userFieldName});
        $response->assertSeeText($receipt->shift->cashRegister->title);
        $response->assertSeeText($receipt->shift->cashier->full_name);
    }
}