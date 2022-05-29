<?php

declare(strict_types=1);

namespace Kaca\Contracts\Shift;

use Illuminate\Contracts\Auth\Authenticatable;

interface OpenShifts
{
    /**
     * Handle open shift
     */
    public function open(Authenticatable $authenticatable, string $uuid): void;
}
