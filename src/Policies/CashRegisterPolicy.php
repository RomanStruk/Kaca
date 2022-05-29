<?php

declare(strict_types=1);

namespace Kaca\Policies;

use Kaca\Models\CashRegister;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;

class CashRegisterPolicy
{
    use HandlesAuthorization;

    /**
     * Політика на видалення каси
     */
    public function delete(Authenticatable $user, CashRegister $cashRegister): bool
    {
        if ($cashRegister->users()->exists()) {
            return false;
        }
        if ($cashRegister->shifts()->exists()) {
            return false;
        }
        return Gate::forUser($user)->allows('seniorPermission');
    }
}
