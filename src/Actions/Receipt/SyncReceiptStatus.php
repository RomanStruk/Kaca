<?php

declare(strict_types=1);

namespace Kaca\Actions\Receipt;

use Kaca\Contracts\Receipt\SyncReceiptsStatuses;
use Kaca\Jobs\Receipt\GetReceiptCheckbox;
use Kaca\Kaca;

class SyncReceiptStatus implements SyncReceiptsStatuses
{
    public function sync(): void
    {
        foreach (Kaca::findReceiptsForSync() as $receipt) {
            GetReceiptCheckbox::dispatch($receipt);
        }
    }
}
