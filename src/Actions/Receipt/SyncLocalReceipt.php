<?php

declare(strict_types=1);

namespace Kaca\Actions\Receipt;

use Kaca\Contracts\Receipt\SyncLocalReceipts;
use Kaca\Events\ReceiptSynchronizedWithStatusDone;
use Kaca\Events\RefundReceiptSynchronizedWithStatusDone;
use Kaca\Models\Receipt;
use Kaca\Synchronization;

class SyncLocalReceipt implements SyncLocalReceipts
{
    /**
     * Поля на оновлення
     */
    protected array $fields = [
        'type',
        'serial',
        'status',
        'total_sum',
        'total_payment',
        'fiscal_code',
    ];

    /**
     * Оновлення локального чека даними які пришли з сервера Checkbox
     */
    public function sync(Receipt $receipt, array $response)
    {
        $receipt->fill(
            collect($response)
                ->only($this->fields)
                ->toArray()
        );
        $receipt->save();

        Synchronization::resolve($receipt->status, $receipt->id);

        $this->dispatchEvents($receipt);
    }

    /**
     * Створення подій
     */
    protected function dispatchEvents(Receipt $receipt): void
    {
        if ($receipt->wasSold() && $receipt->status === 'DONE') {
            ReceiptSynchronizedWithStatusDone::dispatch($receipt);
        }
        if ($receipt->wasRefunded() && $receipt->status === 'DONE') {
            RefundReceiptSynchronizedWithStatusDone::dispatch($receipt);
        }
    }
}
