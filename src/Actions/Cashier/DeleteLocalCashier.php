<?php

declare(strict_types=1);

namespace Kaca\Actions\Cashier;

use Illuminate\Contracts\Auth\Authenticatable;
use Kaca\ActionRecorder;
use Kaca\Models\Cashier;

class DeleteLocalCashier
{
    public function delete(Authenticatable $authenticatable, Cashier $cashier): bool
    {
        ActionRecorder::deleting($authenticatable, Cashier::class, $cashier->id);

        return $cashier->delete();
    }
}
