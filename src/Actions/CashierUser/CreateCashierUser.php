<?php

declare(strict_types=1);

namespace Kaca\Actions\CashierUser;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Kaca\Kaca;

class CreateCashierUser
{
    /**
     * Granting rights to use the cash register for the user
     */
    public function create(array $attributes): Authenticatable
    {
        $validated = Validator::make($attributes, [
            'user_id' => ['required', Rule::exists(Kaca::newUserModel()->getTable(), 'id')],
            'cashier_id' => ['required', 'exists:cashiers,id'],
            'cash_register_id' => ['required', 'exists:cash_registers,id',],
        ])->validate();

        $user = Kaca::findUserByIdOrFail(intval($validated['user_id']));
        $user->cashier_id = $validated['cashier_id'];
        $user->cash_register_id = $validated['cash_register_id'];
        $user->save();

        return $user;
    }
}
