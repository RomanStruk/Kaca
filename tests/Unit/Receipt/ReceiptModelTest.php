<?php

namespace Kaca\Tests\Unit\Receipt;

use Illuminate\Support\Str;
use Kaca\Database\Factories\ReceiptFactory;
use Kaca\Database\Factories\ReceiptGoodFactory;
use Kaca\Database\Factories\ReceiptPaymentFactory;
use Kaca\Models\Receipt;
use Kaca\Models\ReceiptPayment;
use Kaca\Tests\TestCase;

class ReceiptModelTest extends TestCase
{
    /** @test */
    public function it_correct_build_checkbox_request()
    {
        $receipt = ReceiptFactory::new()
            ->has(ReceiptGoodFactory::new()->count(2), 'receiptGoods')
            ->has(ReceiptPaymentFactory::new()->count(1), 'receiptPayments')
            ->create();
//        dd($receipt->toCheckbox());
        $this->assertIsArray($receipt->toCheckbox());
    }

    /** @test */
    public function it_create_receipt_from_request_data()
    {
        $shift = $this->setUpOpenedShiftWithUser();
        $data = [
            'id' => $id = Str::uuid()->toString(),
            'deliveries' => [
                'emails' => ['some@mil.com'],
            ],
            'goods' => [
                [
                    "code" => 123,
                    "name" => 'Product 1',
                    "quantity" => 1000,
                    "price" => 100,
                    'is_return' => false,
                ],
            ],
        ];
        $receipt = new Receipt([
            'id' => $id,
            'delivery' => $data['deliveries'],
        ]);
        $shift->receipts()->save($receipt);
        $receipt->receiptGoods()->createMany($data['goods']);
        $receipt->receiptPayments()->create(['value' => $receipt->receiptGoods->getTotalSum()->getPrice()]);

        $this->assertIsArray($receipt->toCheckbox());
    }
}