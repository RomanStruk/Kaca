<?php

declare(strict_types=1);

namespace Kaca\Contracts\Shift;

use Kaca\Contracts\SyncStatuses;
use Kaca\Models\Shift;

interface SyncShiftsStatuses extends SyncStatuses
{
    public function dispatch(Shift $shift): void;
}
