<?php

declare(strict_types=1);

namespace Kaca\Contracts\Shift;

use Illuminate\Contracts\Auth\Authenticatable;
use Kaca\Models\Shift;

interface CloseShifts
{
    /**
     * Handle close shift
     */
    public function close(Authenticatable $authenticatable, Shift $shift): void;
}
