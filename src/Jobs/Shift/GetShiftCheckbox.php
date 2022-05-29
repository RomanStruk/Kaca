<?php

declare(strict_types=1);

namespace Kaca\Jobs\Shift;

use Kaca\Actions\Shift\UpdateLocalShift;
use Kaca\CheckboxApiFacade;
use Kaca\Contracts\CheckboxExceptions;
use Kaca\Models\Shift;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetShiftCheckbox implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Shift $shift;

    public function __construct(Shift $shift)
    {
        $this->queue = config('kaca.queue');
        $this->shift = $shift;
    }

    /**
     * @throws GuzzleException
     * @throws CheckboxExceptions
     */
    public function handle(): void
    {
        // request to checkbox.ua
        $response = CheckboxApiFacade::setBearerToken($this->shift->cashier->getAccessToken())
            ->getShift($this->shift->getUuid());

        // after update local shift
        app(UpdateLocalShift::class)->update($this->shift, $response);
    }
}
