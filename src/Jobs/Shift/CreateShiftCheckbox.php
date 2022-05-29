<?php

declare(strict_types=1);

namespace Kaca\Jobs\Shift;

use Kaca\Actions\Shift\UpdateLocalShift;
use Kaca\CheckboxApiFacade;
use Kaca\Contracts\CheckboxExceptions;
use Kaca\Models\Cashier;
use Kaca\Models\CashRegister;
use Kaca\Models\Shift;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateShiftCheckbox implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Shift $shift;

    protected Cashier $cashier;

    protected CashRegister $cashRegister;

    public function __construct(Shift $shift, Cashier $cashier, CashRegister $cashRegister)
    {
        $this->queue = config('kaca.queue');
        $this->shift = $shift;
        $this->cashier = $cashier;
        $this->cashRegister = $cashRegister;
    }

    /**
     * Відправляємо запит на сервіс на відкриття каси
     * @return void
     * @throws CheckboxExceptions
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(): void
    {
        $api = CheckboxApiFacade::setBearerToken($this->cashier->getAccessToken())
            ->withLicenseKey($this->cashRegister->getLicenceKey());
        $response = $api->createShift($this->shift->getUuid());

        // після відповіді оновити локально
        app(UpdateLocalShift::class)->update($this->shift, $response);
    }
}
