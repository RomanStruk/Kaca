<?php

declare(strict_types=1);

namespace Kaca\Jobs\Shift;

use Kaca\Actions\Shift\UpdateLocalShift;
use Kaca\CheckboxApiFacade;
use Kaca\Models\Shift;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CloseShiftCheckbox implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Shift $shift;

    public function __construct(Shift $shift)
    {
        $this->queue = config('kaca.queue');
        $this->shift = $shift;
    }

    public function handle(): void
    {
        $api = CheckboxApiFacade::setBearerToken($this->shift->cashier->getAccessToken());
        $response = $api->closeShift($this->shift->getUuid());

        // після відповіді оновити локально
        app(UpdateLocalShift::class)->update($this->shift, $response);
    }
}
