<?php

namespace Kaca\Tests\Unit\Receipt;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Kaca\Contracts\Receipt\CreatesLocalReceipts;
use Kaca\Contracts\Receipt\CreatesReceipts;
use Kaca\Contracts\Receipt\SyncLocalReceipts;
use Kaca\Database\Factories\ReceiptFactory;
use Kaca\Database\Factories\ReceiptGoodFactory;
use Kaca\Database\Factories\ReceiptPaymentFactory;
use Kaca\Helpers\ReceiptGoodCollection;
use Kaca\Helpers\ReceiptPaymentCollection;
use Kaca\Jobs\Receipt\CreateReceiptCheckbox;
use Kaca\Models\Receipt;
use Kaca\Models\ReceiptGood;
use Kaca\Models\ReceiptPayment;
use Kaca\Synchronization;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestResponses;

class ReceiptTest extends TestCase
{
    /** @test */
    public function it_create_local_receipt_with_goods_relation()
    {
        $shift = $this->setUpOpenedShiftWithUser();

        $data = [
            "deliveries" => [
                "emails" => ["roma@webmaestro.com"],
            ],
            'goods' => [
                [
                    "code" => '123',
                    "name" => 'Product 1',
                    "quantity" => '1',
                    "price" => '1000', // 1000 грн
                    'is_return' => false,
                ]
            ]
        ];

        $goods = ReceiptGoodCollection::make($data['goods'])
            ->map(function (array $data) {
                return new ReceiptGood($data);
            });
        $receipt = app(CreatesLocalReceipts::class)->create(
            $shift,
            Str::uuid()->toString(),
            $data['deliveries'],
            $goods
        );

        $this->assertCount(1, $shift->receipts);
        $this->assertSame($data['deliveries'], $receipt->delivery);

        $this->assertEquals(1000, $receipt->getTotalPayment()->getPriceInUAH());
        $this->assertEquals(1000, $receipt->getTotalSum()->getPriceInUAH());
        $this->assertEquals('CREATED', $receipt->status);
        $this->assertEquals(Synchronization::STATUS_CREATED, Synchronization::getStatusFor($receipt->id));
    }

    /** @test */
    public function it_sync_local_receipt_after_request()
    {
        $response = TestResponses::$receipt_sell_created;

        $this->travelTo(now()->setDate(2022, 2, 5));
        $shift = $this->setUpOpenedShiftWithUser();
        $receipt = ReceiptFactory::new(['id' => $response['id']])
            ->for($shift, 'shift')
            ->has(ReceiptGoodFactory::new([
                'price' => TestResponses::$receipt_sell_created['total_sum']/100,
                'quantity' => 1,
            ])->count(1), 'receiptGoods')
            ->has(ReceiptPaymentFactory::new([
                'value' => TestResponses::$receipt_sell_created['total_payment']/100,
            ])->count(1), 'receiptPayments')
            ->create();

        app(SyncLocalReceipts::class)->sync($receipt, $response);
        $receipt->refresh();

        $this->assertEquals($response['status'], $receipt->status);
        $this->assertEquals($response['serial'], $receipt->serial);
        $this->assertEquals($response['total_sum'], $receipt->getTotalSum()->getPrice());
        $this->assertEquals($response['total_payment'], $receipt->getTotalPayment()->getPrice());
        $this->assertEquals($response['fiscal_code'], $receipt->fiscal_code);

        $this->assertEquals(Synchronization::STATUS_CREATED, Synchronization::getStatusFor($receipt->id));

    }

    /** @test */
    public function it_dispatched_job_send_request()
    {
        $this->travelTo(now()->setDate(2022, 2, 5));
        $shift = $this->setUpOpenedShiftWithUser();
        $receipt = ReceiptFactory::new()
            ->for($shift, 'shift')
            ->has(ReceiptGoodFactory::new()->count(2), 'receiptGoods')
            ->has(ReceiptPaymentFactory::new()->count(1), 'receiptPayments')
            ->create();

        Http::fake([
            '*/api/v1/receipts/*' => Http::sequence()
                ->push(TestResponses::$receipt_sell_created),
            '*' => Http::response('', 200, []),
        ]);

        $this->assertEquals('CREATED', $receipt->status);
        $this->assertNull($receipt->serial);

        CreateReceiptCheckbox::dispatch($receipt, $receipt->toCheckbox());

        $receipt->refresh();
        $this->assertEquals('CREATED', $receipt->status);
        $this->assertEquals('9', $receipt->serial);

        $this->assertEquals(Synchronization::STATUS_CREATED, Synchronization::getStatusFor($receipt->id));
    }

    /** @test */
    public function it_create_receipt()
    {
        $this->travelTo(now()->setDate(2022, 2, 5));

        $shift = $this->setUpOpenedShiftWithUser();
        Http::fake([
            '*/api/v1/receipts/*' => Http::sequence()
                ->push(TestResponses::$receipt_sell_donne),
            '*' => Http::response('', 200, []),
        ]);

        $data = [
            "deliveries" => [
                "emails" => ["roma@webmaestro.com"],
            ],
            'payments' => [
                [
                    'type' => 'CASHLESS',
                    'value' => '1000', // 1000 грн
                    'label' => 'Credit cart',
                ]
            ],
            'goods' => [
                [
                    "code" => '123',
                    "name" => 'Product 1',
                    "quantity" => 1,
                    "price" => '1000', // 1000 грн
                    'is_return' => false,
                ]
            ]
        ];
        $goods = ReceiptGoodCollection::make($data['goods'])
            ->map(function (array $data) {
                return new ReceiptGood($data);
            });
        $receipt = app(CreatesReceipts::class)
            ->with(['order_id' =>'9999', 'reverse_compatibility_data' => '[2222, 2223]'])
            ->create(
                auth()->user(),
                TestResponses::$receipt_sell_donne['id'],
                $data['deliveries'],
                $goods,
                ReceiptPaymentCollection::make([new ReceiptPayment($data['payments'][0])])
            );

        $receipt->refresh();
        $this->assertEquals('7f28b06b-e81d-4f1d-9b90-406df1279fdf', $receipt->id);
        $this->assertDatabaseCount(Receipt::class, 1);
        $this->assertTrue($receipt->wasSold());
        $this->assertEquals('9999', $receipt->order_id);
        $this->assertEquals('[2222, 2223]', $receipt->reverse_compatibility_data);
    }

    /** @test */
    public function sync_command_can_update_local_receipt()
    {
        $this->travelTo(now()->setDate(2022, 2, 5));

        $shift = $this->setUpOpenedShiftWithUser();
        $receipt = ReceiptFactory::new([
            'id' => '7f28b06b-e81d-4f1d-9b90-406df1279fdf',
            'serial' => 9
        ])
            ->for($shift, 'shift')
            ->has(ReceiptGoodFactory::new()->count(2), 'receiptGoods')
            ->create();

        $this->assertDatabaseCount(Receipt::class, 1);
        $this->assertEquals('CREATED', $receipt->status);
        $this->assertNull($receipt->fiscal_code);
        $this->assertEquals('9', $receipt->serial);
        $this->assertEquals('7f28b06b-e81d-4f1d-9b90-406df1279fdf', $receipt->id);
        $this->assertEquals(Synchronization::STATUS_CREATED, Synchronization::getStatusFor($receipt->id));

        Http::fake([
            '*/api/v1/receipts/*' => Http::sequence()
                ->push(TestResponses::$receipt_sell_donne),
            '*/api/v1/shifts/*' => Http::response(TestResponses::$shift_status_opened),
            '*' => Http::response('', 200, []),
        ]);

        // синхронізація
        $this->artisan('kaca:process');

        $receipt->refresh();
        $this->assertDatabaseCount(Receipt::class, 1);
        $this->assertEquals('DONE', $receipt->status);
        $this->assertEquals('TEST-NHxviw', $receipt->fiscal_code);
        $this->assertEquals('9', $receipt->serial);
        $this->assertEquals('7f28b06b-e81d-4f1d-9b90-406df1279fdf', $receipt->id);
        $this->assertTrue(Synchronization::isAvailable($receipt->id));
    }

    /** @test */
    public function it_correctly_work_with_create_failed_request()
    {
        $this->travelTo(now()->setDate(2022, 2, 5));

        $shift = $this->setUpOpenedShiftWithUser();
        Http::fake([
            '*/api/v1/receipts/*' => Http::sequence()
                ->push(TestResponses::$invalid_receipt_validation, 422),
            '*' => Http::response('', 200, []),
        ]);

        $data = [
            "deliveries" => [
                "emails" => ["roma@webmaestro.com"],
            ],
            'payments' => [
                [
                    'type' => 'CASHLESS',
                    'value' => '1000', // 1000 грн
                    'label' => 'Credit cart',
                ]
            ],
            'goods' => [
                [
                    "code" => '123',
                    "name" => 'Product 1',
                    "quantity" => 1,
                    "price" => '1000', // 1000 грн
                    'is_return' => false,
                ]
            ]
        ];
        $goods = ReceiptGoodCollection::make($data['goods'])
            ->map(function (array $data) {
                return new ReceiptGood($data);
            });
        $receipt = app(CreatesReceipts::class)->create(
            auth()->user(),
            '7f28b06b-e81d-4f1d-9b90-406df1279fdf',
            $data['deliveries'],
            $goods
        );

        $this->assertEquals('ERROR', $receipt->status);
        $this->assertTrue(Synchronization::isAvailable($receipt->id));
    }
}
