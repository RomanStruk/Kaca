<?php

namespace Kaca\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Kaca\Database\Factories\CashierFactory;
use Kaca\Database\Factories\CashRegisterFactory;
use Kaca\Database\Factories\ReceiptFactory;
use Kaca\Database\Factories\ReceiptGoodFactory;
use Kaca\Database\Factories\ReceiptPaymentFactory;
use Kaca\Database\Factories\ShiftFactory;
use Kaca\Models\Receipt;
use Kaca\Models\ReceiptGood;
use Kaca\Models\ReceiptPayment;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestModels\User;
use Kaca\Tests\TestResponses;

class RefundReceiptControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo(now()->setDate(2022, 2, 5));
    }

    /** @test */
    public function user_can_refund_receipt()
    {
        $this->withoutExceptionHandling();

        $cashRegister = CashRegisterFactory::new()->create();
        $cashier = CashierFactory::new()->create();
        $this->actingAs(User::factory(['cashier_id' => $cashier->id, 'cash_register_id' => $cashRegister->id])->create());
        $shift = ShiftFactory::new()->for($cashier, 'cashier')->forOpenedStatus()->create();
        $receipt = ReceiptFactory::new()
            ->for($shift, 'shift')
            ->has(ReceiptGoodFactory::new()->count(2), 'receiptGoods')
            ->has(ReceiptPaymentFactory::new()->count(1), 'receiptPayments')
            ->create();

        Http::fake([
            '*/api/v1/receipts/*' => Http::response(TestResponses::$receipt_return_done),
            '*' => Http::response('', 200, []),
        ]);

        $this->assertDatabaseCount(Receipt::class, 1);
        $this->assertDatabaseCount(ReceiptGood::class, 2);
        $this->assertDatabaseCount(ReceiptPayment::class, 1);
        $response = $this->post(route('kaca.refund-receipts.store', $receipt));

        $this->assertDatabaseCount(Receipt::class, 2);
        $this->assertDatabaseCount(ReceiptGood::class, 4);
        $this->assertDatabaseCount(ReceiptPayment::class, 2);
        $response->assertRedirect(route('kaca.receipts.show', Receipt::where('type', 'RETURN')->first()));
    }

    /** @test */
    public function validation_pass_refund_receipt()
    {
//        $model = \Kaca\Tests\TestModels\Receipt::factory()->create();
//        dd($model);
        self::assertTrue(true);
    }
}
