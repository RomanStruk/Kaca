<?php

declare(strict_types=1);

namespace Kaca\Actions\Shift;

use Illuminate\Support\Facades\Bus;
use Kaca\Contracts\Shift\SyncShiftsStatuses;
use Kaca\Jobs\Shift\GetShiftCheckbox;
use Kaca\Kaca;
use Kaca\Models\Shift;
use Kaca\Synchronization;
use Throwable;

class SyncShiftStatus implements SyncShiftsStatuses
{
    public function sync(): void
    {
        foreach (Kaca::findShiftsForSync() as $shift) {
            $this->dispatch($shift);
        }
    }

    public function dispatch(Shift $shift): void
    {
        // заблокувати зміну від змін
        Synchronization::begin($shift->getUuid());

        Bus::chain([
            new GetShiftCheckbox($shift),
        ])->catch(function (Throwable $e) use ($shift) {
            // після помилки розблокувати
            Synchronization::failed($shift->getUuid());
            throw $e;
        })->dispatch();
    }
}
