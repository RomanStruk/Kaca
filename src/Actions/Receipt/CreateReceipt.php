<?php

declare(strict_types=1);

namespace Kaca\Actions\Receipt;

use Illuminate\Contracts\Auth\Authenticatable;
use Kaca\ActionRecorder;
use Kaca\Contracts\Receipt\CreatesLocalReceipts;
use Kaca\Contracts\Receipt\CreatesReceipts;
use Kaca\Helpers\ReceiptGoodCollection;
use Kaca\Helpers\ReceiptPaymentCollection;
use Kaca\Jobs\Receipt\CreateReceiptCheckbox;
use Kaca\Kaca;
use Kaca\Models\Receipt;

class CreateReceipt implements CreatesReceipts
{
    protected CreatesLocalReceipts $createsLocalReceipts;

    public function __construct(CreatesLocalReceipts $createsLocalReceipts)
    {
        $this->createsLocalReceipts = $createsLocalReceipts;
    }

    /**
     * Create receipt
     */
    public function create(
        Authenticatable $authenticatable,
        string $uuid,
        array $deliveries,
        ReceiptGoodCollection $receiptGoods,
        ?ReceiptPaymentCollection $receiptPayments = null
    ): Receipt
    {
        // log action
        ActionRecorder::creating($authenticatable, Receipt::class, $uuid);

        $shift = Kaca::findShiftByCashierUser($authenticatable);

        // create local record about receipt
        $receipt = $this->createsLocalReceipts
            ->create($shift, $uuid, $deliveries, $receiptGoods, $receiptPayments);

        // send receipt request to checkbox.ua
        CreateReceiptCheckbox::dispatch($receipt, $receipt->toCheckbox());

        return $receipt->fresh();
    }

    /**
     * Additional parameters for local receipt
     */
    public function with(array $data = []): CreatesReceipts
    {
        $this->createsLocalReceipts->with($data);

        return $this;
    }
}
