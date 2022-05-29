<?php

namespace Kaca\Jobs\Cashier;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kaca\Actions\Shift\UpdateLocalShift;
use Kaca\CheckboxApiFacade;
use Kaca\Contracts\Shift\SyncShiftsStatuses;
use Kaca\Exception\CheckboxExceptions;
use Kaca\Models\Cashier;
use Kaca\Models\Shift;
use Kaca\Synchronization;

class GetCashierShiftCheckboxJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Cashier $cashier;

    public function __construct(Cashier $cashier)
    {
        $this->queue = config('kaca.queue');
        $this->cashier = $cashier;
    }

    public function handle()
    {
        // якщо є зміни які в статусі відкрито, то виходимо і чекаємо поки оновиться статус
        $collection = Synchronization::findWithStatus(Shift::class, Synchronization::STATUS_CREATED);
        if ($collection->where('cashier_id', '=', $this->cashier->id)->count() === 1) {
            return;
        }

        // create request to checkbox.ua
        try {
            $response = CheckboxApiFacade::setBearerToken($this->cashier->getAccessToken())
                ->getCashierShift();
            // get local shift from response id
            $shift = $this->findShift($response['id']);

            if ($shift) {
                // if already exists than update that
                app(UpdateLocalShift::class)->update($shift, $response);
            } else {
                // else create local record about it
                app(UpdateLocalShift::class)->update($this->cashier->shift, $response);
            }
        } catch (CheckboxExceptions $checkboxExceptions) {
            // якщо пустий результат то відповідно немає інформації про зміни для касира

            // але якщо є локально інформація про відкриту зміну то потрібно
            // відправити запит на отримання деталей про зміну
            if ($this->cashier->shift->isOpen()) {
                app(SyncShiftsStatuses::class)->dispatch($this->cashier->shift);
            }
        }
    }

    protected function findShift(string $id): ?Shift
    {
        return Shift::where('id', '=', $id)->first();
    }
}
