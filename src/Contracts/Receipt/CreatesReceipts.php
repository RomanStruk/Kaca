<?php

declare(strict_types=1);

namespace Kaca\Contracts\Receipt;

use Illuminate\Contracts\Auth\Authenticatable;
use Kaca\Helpers\ReceiptGoodCollection;
use Kaca\Helpers\ReceiptPaymentCollection;
use Kaca\Models\Receipt;

interface CreatesReceipts
{
    /**
     * Create receipt by receipt dto
     */
    public function create(
        Authenticatable $authenticatable,
        string $uuid,
        array $deliveries,
        ReceiptGoodCollection $receiptGoods,
        ?ReceiptPaymentCollection $receiptPayments = null
    ): Receipt;

    /**
     * Additional parameters for receipt
     */
    public function with(array $data = []): CreatesReceipts;
}
