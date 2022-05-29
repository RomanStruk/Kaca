<?php

declare(strict_types=1);

namespace Kaca\Actions\CashierUser;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Validator;

class UpdateCashierUser
{
    /**
     * Оновлення каси та касира для користувача
     */
    public function update(Authenticatable $authenticatable, array $attributes): bool
    {
        $validated = Validator::make($attributes, [
            'cashier_id' => ['required', 'exists:cashiers,id'],
            'cash_register_id' => ['required', 'exists:cash_registers,id',],
        ])->validate();

        $authenticatable->cashier_id = $validated['cashier_id'];
        $authenticatable->cash_register_id = $validated['cash_register_id'];

        return $authenticatable->save();
    }
}
