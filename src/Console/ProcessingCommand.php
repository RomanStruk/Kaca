<?php

declare(strict_types=1);

namespace Kaca\Console;

use Illuminate\Console\Command;
use Kaca\Contracts\Receipt\SyncReceiptsStatuses;
use Kaca\Contracts\Shift\SyncShiftsStatuses;

class ProcessingCommand extends Command
{
    protected $signature = 'kaca:process';

    protected $description = 'Run checking statuses and sync';

    public function handle(SyncShiftsStatuses $syncShiftsStatuses, SyncReceiptsStatuses $syncReceiptsStatuses)
    {
        $syncReceiptsStatuses->sync(); // Синхронізація чеків
        $syncShiftsStatuses->sync();  // Синхронізація змін

        return 0;
    }
}
