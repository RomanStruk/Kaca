<?php

declare(strict_types=1);

namespace Kaca\Actions\CashierUser;

use Illuminate\Contracts\Auth\Authenticatable;

class DeleteCashierUser
{
    /**
     * Видалення каси та касира для користувача
     */
    public function delete(Authenticatable $authenticatable): bool
    {
        $authenticatable->cashier_id = null;
        $authenticatable->cash_register_id = null;

        return $authenticatable->save();
    }
}
