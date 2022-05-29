<?php

declare(strict_types=1);

namespace Kaca\Actions\CashRegister;

use Illuminate\Contracts\Auth\Authenticatable;
use Kaca\ActionRecorder;
use Kaca\CheckboxApiFacade;
use Kaca\Models\CashRegister;

class SyncCashRegister
{
    public function sync(Authenticatable $authenticatable, string $licenceKey): CashRegister
    {
        $api = CheckboxApiFacade::withLicenseKey($licenceKey);
        $response = $api->getCashRegisterInfo();

        $cashRegister = app(SyncLocalCashRegister::class)->sync($licenceKey, $response);

        if ($cashRegister->wasRecentlyCreated) {
            ActionRecorder::creating($authenticatable, CashRegister::class, $cashRegister->id);
        } else {
            ActionRecorder::updating($authenticatable, CashRegister::class, $cashRegister->id);
        }

        return $cashRegister;
    }
}
