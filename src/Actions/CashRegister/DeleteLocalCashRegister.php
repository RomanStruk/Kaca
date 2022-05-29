<?php

declare(strict_types=1);

namespace Kaca\Actions\CashRegister;

use Illuminate\Contracts\Auth\Authenticatable;
use Kaca\ActionRecorder;
use Kaca\Models\CashRegister;

class DeleteLocalCashRegister
{
    public function delete(Authenticatable $authenticatable, CashRegister $cashRegister): bool
    {
        ActionRecorder::deleting($authenticatable, CashRegister::class, $cashRegister->id);

        return $cashRegister->delete();
    }
}
