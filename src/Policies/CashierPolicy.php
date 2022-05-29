<?php

declare(strict_types=1);

namespace Kaca\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Kaca\Models\Cashier;

class CashierPolicy
{
    use HandlesAuthorization;

    public function view($user): bool
    {
        return !is_null($user->cashier_id);
    }

    /**
     * Політика видалення касира
     */
    public function delete($user, Cashier $cashier): bool
    {
        if ($cashier->users()->exists()) {
            return false;
        }
        if ($cashier->shifts()->exists()) {
            return false;
        }
        return true;
    }
}
