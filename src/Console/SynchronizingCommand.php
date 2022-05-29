<?php

declare(strict_types=1);

namespace Kaca\Console;

use Illuminate\Console\Command;
use Kaca\Jobs\Cashier\GetCashierShiftCheckboxJob;
use Kaca\Models\Cashier;

class SynchronizingCommand extends Command
{
    protected $signature = 'kaca:synchronizing';

    protected $description = 'Synchronizing entries';

    public function handle(): int
    {
        foreach (Cashier::all() as $cashier) {
            GetCashierShiftCheckboxJob::dispatch($cashier);
        }
        return 0;
    }
}
