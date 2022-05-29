<?php

declare(strict_types=1);

namespace Kaca\Actions\Shift;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Bus;
use Kaca\ActionRecorder;
use Kaca\Contracts\Shift\OpenShifts;
use Kaca\Jobs\Shift\CreateShiftCheckbox;
use Kaca\Kaca;
use Kaca\Models\Cashier;
use Kaca\Models\CashRegister;
use Kaca\Models\Shift;
use Kaca\Synchronization;
use Throwable;

class OpenShift implements OpenShifts
{
    /**
     * Handle open shift
     */
    public function open(Authenticatable $authenticatable, string $uuid): void
    {
        ActionRecorder::creating($authenticatable, Shift::class, $uuid);

        $cashier = Kaca::findCashierByCashierUser($authenticatable);
        $cashRegister = Kaca::findCashRegisterByCashierUser($authenticatable);

        // створити локально запис
        $localShift = $this->create($uuid, $cashier, $cashRegister);

        Synchronization::begin($localShift->getUuid());

        // створити запит на сервіс
        Bus::chain([
            new CreateShiftCheckbox($localShift, $cashier, $cashRegister),
        ])->catch(function (Throwable $e) use ($localShift) {
            $localShift->delete();
            throw $e;
        })->dispatch();
    }

    /**
     * Створення локальної зніми зі статусом що синхронізується
     */
    protected function create(string $uuid, Cashier $cashier, CashRegister $cashRegister): Shift
    {
        $cashier->shift()->create([
            'id' => $uuid,
            'serial' => 0,
            'status' => 'CREATED',
            'cash_register_id' => $cashRegister->id,
        ]);

        $cashier->load('shift');

        return $cashier->shift;
    }
}
