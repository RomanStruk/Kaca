<?php

declare(strict_types=1);

namespace Kaca\Policies;

use Kaca\Kaca;
use Kaca\Models\Report;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    public function viewAny($user): bool
    {
        return !is_null($user->cashier_id);
    }

    public function view($user, Report $report): bool
    {
        return !is_null($user->cashier_id);
    }

    public function create($user): bool
    {
        return !is_null($user->cashier_id) && Kaca::findShiftByCashierUser($user)->isOpen();
    }
}
