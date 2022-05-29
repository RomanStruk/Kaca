<?php

namespace Kaca\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Kaca\ActionRecorder;
use Kaca\Database\Factories\CashierFactory;
use Kaca\Database\Factories\CashRegisterFactory;
use Kaca\Database\Factories\ReceiptFactory;
use Kaca\Database\Factories\ReceiptGoodFactory;
use Kaca\Database\Factories\ShiftFactory;
use Kaca\Kaca;
use Kaca\Models\Receipt;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestModels\User;
use Kaca\Tests\TestResponses;
use function route;

class ReceiptControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo(now()->setDate(2022, 2, 5));
    }

    /** @test */
    public function user_can_create_receipt()
    {
        $this->withoutExceptionHandling();
        $cashRegister = CashRegisterFactory::new()->create();
        $cashier = CashierFactory::new()->create();
        $this->actingAs($user = User::factory(['cashier_id' => $cashier->id, 'cash_register_id' => $cashRegister->id])->create());
        ShiftFactory::new(['cashier_id' => $cashier->id])->create();

        // підмінити відповідь для сервісу
        Http::fake([
            '*/api/v1/receipts/*' => Http::response(TestResponses::$receipt_sell_donne),
            '*' => Http::response('', 200, []),
        ]);

        $data = [
            'id' => $id = Str::uuid()->toString(),
            'deliveries' => [
                'emails' => ['some@mil.com'],
            ],
            'goods' => [
                [
                    "code" => 123,
                    "name" => 'Product 1',
                    "quantity" => 1,
                    "price" => 100,
                    'is_return' => false,
                ],
            ],
        ];

        $this->assertDatabaseCount(Receipt::class, 0);

        $response = $this->post(route('kaca.receipts.store', $data));

        $response->assertRedirect(route('kaca.receipts.show', ['receipt' => $id]));
        $this->assertDatabaseCount(Receipt::class, 1);

        $this->get(route('kaca.receipts.show', $id))
            ->assertSee(TestResponses::$receipt_sell_donne['fiscal_code']);
    }

    /** @test */
    public function it_show_receipt()
    {
        $cashRegister = CashRegisterFactory::new()->create();
        $cashier = CashierFactory::new()->create();
        $this->actingAs($user = User::factory(['cashier_id' => $cashier->id, 'cash_register_id' => $cashRegister->id])->create());
        $shift = ShiftFactory::new()
            ->for($cashier, 'cashier')
            ->for($cashRegister, 'cashRegister')
            ->forOpenedStatus()
            ->create();

        $receipt = ReceiptFactory::new()
            ->for($shift, 'shift')
            ->has(ReceiptGoodFactory::new()->count(2), 'receiptGoods')
            ->create();

        $response = $this->get(route('kaca.receipts.show', $receipt));
        $response->assertOk();
        $response->assertSee($receipt->fiscal_code);
    }

    /** @test */
    public function it_see_receipt_on_index_page()
    {
        $cashRegister = CashRegisterFactory::new()->create();
        $cashier = CashierFactory::new()->create();
        $this->actingAs($user = User::factory(['cashier_id' => $cashier->id, 'cash_register_id' => $cashRegister->id])->create());
        $shift = ShiftFactory::new()
            ->for($cashier, 'cashier')
            ->for($cashRegister, 'cashRegister')
            ->forOpenedStatus()
            ->create();

        $receipt = ReceiptFactory::new()
            ->for($shift, 'shift')
            ->has(ReceiptGoodFactory::new()->count(2), 'receiptGoods')
            ->create();

        $response = $this->get(route('kaca.receipts.index'));
        $response->assertOk();
        $response->assertSee($receipt->fiscal_code);
    }

    /** @test */
    public function it_filtered_receipt_by_order_id()
    {
        $cashRegister = CashRegisterFactory::new()->create();
        $cashier = CashierFactory::new()->create();
        $this->actingAs($user = User::factory(['cashier_id' => $cashier->id, 'cash_register_id' => $cashRegister->id])->create());
        $shift = ShiftFactory::new()
            ->for($cashier, 'cashier')
            ->for($cashRegister, 'cashRegister')
            ->forOpenedStatus()
            ->create();

        $receipt = ReceiptFactory::new()
            ->for($shift, 'shift')
            ->has(ReceiptGoodFactory::new()->count(2), 'receiptGoods')
            ->create();
        $receipt2 = ReceiptFactory::new(['order_id' => 1])
            ->for($shift, 'shift')
            ->has(ReceiptGoodFactory::new()->count(2), 'receiptGoods')
            ->create();

        $response = $this->get(route('kaca.receipts.index'));
        $response->assertOk();
        $response->assertSee($receipt->fiscal_code);
        $response->assertSee($receipt2->fiscal_code);

        $response = $this->get(route('kaca.receipts.index',['order_id' => 1]));
        $response->assertOk();
        $response->assertDontSee($receipt->fiscal_code);
        $response->assertSee($receipt2->fiscal_code);
    }

    /** @test */
    public function it_shows_the_creator_name_of_the_receipt_on_show_page()
    {
        $cashRegister = CashRegisterFactory::new()->create();
        $cashier = CashierFactory::new()->create();
        $this->actingAs($user = User::factory(['cashier_id' => $cashier->id, 'cash_register_id' => $cashRegister->id])->create());
        $shift = ShiftFactory::new()
            ->for($cashier, 'cashier')
            ->for($cashRegister, 'cashRegister')
            ->forOpenedStatus()
            ->create();
        $receipt = ReceiptFactory::new()
            ->for($shift, 'shift')
            ->has(ReceiptGoodFactory::new()->count(2), 'receiptGoods')
            ->create();
        ActionRecorder::creating($user, Receipt::class, $receipt->id);

        $response = $this->get(route('kaca.receipts.show', $receipt));
        $response->assertOk();
        $response->assertSee($user->{Kaca::$userFieldName});
    }

    /** @test */
    public function it_show_create_receipt_page()
    {
        $cashRegister = CashRegisterFactory::new()->create();
        $cashier = CashierFactory::new()->create();
        $this->actingAs($user = User::factory(['cashier_id' => $cashier->id, 'cash_register_id' => $cashRegister->id])->create());

        $response = $this->get(route('kaca.receipts.create'));
        $response->assertOk();
    }

    /** @test */
    public function it_show_only_auth_user()
    {
        $response = $this->get(route('kaca.receipts.index'), ['Accept'=>'application/json']);
        $response->assertUnauthorized();
    }

    /** @test */
    public function it_show_only_auth_user_with_cashier_permissions()
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->get(route('kaca.receipts.index'), ['Accept'=>'application/json']);
        $response->assertForbidden();
    }
}
