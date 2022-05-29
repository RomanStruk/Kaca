<?php

declare(strict_types=1);

namespace Kaca\Contracts\Receipt;

use Kaca\Helpers\ReceiptGoodCollection;
use Kaca\Helpers\ReceiptPaymentCollection;
use Kaca\Models\Receipt;
use Kaca\Models\Shift;

interface CreatesLocalReceipts
{
    /**
     * Create local receipt
     */
    public function create(
        Shift $shift,
        string $uuid,
        array $deliveries,
        ReceiptGoodCollection $receiptGoods,
        ?ReceiptPaymentCollection $receiptPayments = null
    ): Receipt;

    /**
     * Additional parameters for receipt
     */
    public function with(array $data = []): CreatesLocalReceipts;
}
