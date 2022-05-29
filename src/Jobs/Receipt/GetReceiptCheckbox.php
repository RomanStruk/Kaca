<?php

declare(strict_types=1);

namespace Kaca\Jobs\Receipt;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kaca\CheckboxApiFacade;
use Kaca\Contracts\Receipt\SyncLocalReceipts;
use Kaca\Models\Receipt;

class GetReceiptCheckbox implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Receipt $receipt;

    public function __construct(Receipt $receipt)
    {
        $this->queue = config('kaca.queue');
        $this->receipt = $receipt;
    }

    public function handle(SyncLocalReceipts $syncLocalReceipts): void
    {
        $api = CheckboxApiFacade::setBearerToken($this->receipt->shift->cashier->getAccessToken());
        $response = $api->getReceipt($this->receipt->id);

        $syncLocalReceipts->sync($this->receipt, $response);
    }
}
