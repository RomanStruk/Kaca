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
use Kaca\Exception\CheckboxExceptions;
use Kaca\Models\Receipt;
use Kaca\Synchronization;

class CreateReceiptCheckbox implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Receipt $receipt;
    protected array $requestBody;

    public function __construct(Receipt $receipt, array $requestBody)
    {
        $this->queue = config('kaca.queue');
        $this->receipt = $receipt;
        $this->requestBody = $requestBody;
    }

    /**
     * Створення чеку на сервісі checkbox.ua
     */
    public function handle(SyncLocalReceipts $syncLocalReceipts): void
    {
        try {
            $api = CheckboxApiFacade::setBearerToken($this->receipt->shift->cashier->getAccessToken());
            $response = $api->createReceipt($this->requestBody);

            $syncLocalReceipts->sync($this->receipt, $response);
        } catch (CheckboxExceptions $checkboxExceptions) {
            $this->receipt->status = 'ERROR';
            $this->receipt->save();
            Synchronization::resolve($this->receipt->status, $this->receipt->id);
        }
    }
}
