<?php

declare(strict_types=1);

namespace Kaca\Events;

use Kaca\Models\Receipt;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RefundReceiptSynchronizedWithStatusDone
{
    use Dispatchable, SerializesModels;

    public Receipt $receipt;

    /**
     * Create a new event instance.
     */
    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
    }
}
