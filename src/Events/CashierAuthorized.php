<?php

declare(strict_types=1);

namespace Kaca\Events;

use Kaca\Models\Cashier;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CashierAuthorized
{
    use Dispatchable, SerializesModels;

    public Cashier $cashier;

    public function __construct(Cashier $cashier)
    {
        $this->cashier = $cashier;
    }
}
