<?php

declare(strict_types=1);

namespace Kaca\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Kaca\Models\Shift;
use Kaca\Synchronization;

class ShiftPolicy
{
    use HandlesAuthorization;

    /**
     * CashierUser can open shift
     */
    public function open($user, Shift $shift): bool
    {
        return !$shift->isOpen() && (is_null($shift->id) || \Kaca\Synchronization::isAvailable($shift->id));
    }

    /**
     * CashierUser can close opened shift
     */
    public function close($user, Shift $shift): bool
    {
        return $shift->isOpen() && Synchronization::isAvailable($shift->getUuid());
    }
}
