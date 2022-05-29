<?php

declare(strict_types=1);

namespace Kaca\Actions\Receipt;

use Kaca\Contracts\Receipt\CreatesLocalReceipts;
use Kaca\Helpers\ReceiptGoodCollection;
use Kaca\Helpers\ReceiptPaymentCollection;
use Kaca\Models\Receipt;
use Kaca\Models\ReceiptPayment;
use Kaca\Models\Shift;

class CreateLocalReceipt implements CreatesLocalReceipts
{
    protected array $with = [
        'order_id' => null,
        'reverse_compatibility_data' => null,
        'related_receipt_id' => null,
    ];

    public function create(
        Shift $shift,
        string $uuid,
        array $deliveries,
        ReceiptGoodCollection $receiptGoods,
        ?ReceiptPaymentCollection $receiptPayments = null
    ): Receipt
    {
        $receipt = new Receipt(array_merge([
            'id' => $uuid,
            'shift_id' => $shift->id,
            'delivery' => $deliveries,
        ], $this->with));
        $receipt->save();
        $receipt->receiptGoods()->saveMany($receiptGoods);
        if (is_null($receiptPayments)) {
            $payment = new ReceiptPayment(['value' => $receipt->receiptGoods->getTotalSum(),]);
            $receiptPayments = collect([$payment]);
        }
        $receipt->receiptPayments()->saveMany($receiptPayments);

        return $receipt->fresh();
    }

    /**
     * Additional parameters for receipt
     */
    public function with(array $data = []): CreatesLocalReceipts
    {
        $this->with = $data;

        return $this;
    }
}
