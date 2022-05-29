<?php

declare(strict_types=1);

namespace Kaca\Actions\CashRegister;

use Kaca\Models\CashRegister;

class SyncLocalCashRegister
{
    public function sync(string $licenseKey, array $attributes): CashRegister
    {
        $cashRegister = CashRegister::where('licence_key', '=', $licenseKey)->firstOrNew();

        $cashRegister->fill(
            collect($attributes)
                ->only(['fiscal_number', 'created_at', 'address', 'title', 'id'])
                ->merge(['licence_key' => $licenseKey])
                ->toArray()
        );
        $cashRegister->save();

        return $cashRegister;
    }
}
