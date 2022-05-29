<?php

declare(strict_types=1);

namespace Kaca\Actions\Shift;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Bus;
use Kaca\ActionRecorder;
use Kaca\Contracts\Shift\CloseShifts;
use Kaca\Jobs\Shift\CloseShiftCheckbox;
use Kaca\Models\Shift;
use Kaca\Synchronization;
use Throwable;

class CloseShift implements CloseShifts
{
    /**
     * Handle close shift
     */
    public function close(Authenticatable $authenticatable, Shift $shift): void
    {
        ActionRecorder::updating($authenticatable, Shift::class, $shift->getUuid());

        // заблокувати зміну від змін
        Synchronization::begin($shift->getUuid());

        // створити запит на сервіс
        Bus::chain([
            new CloseShiftCheckbox($shift),
        ])->catch(function (Throwable $e) use ($shift) {
            Synchronization::failed($shift->getUuid());
            throw $e;
        })->dispatch();
    }
}
