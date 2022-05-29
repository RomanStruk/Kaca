<?php

declare(strict_types=1);

namespace Kaca\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Kaca\Kaca;
use Kaca\Models\Receipt;
use Kaca\Synchronization;

class ReceiptPolicy
{
    use HandlesAuthorization;

    public function view($user): bool
    {
        return !is_null($user->cashier_id);
    }

    public function create($user): bool
    {
        $shift = Kaca::findShiftByCashierUser($user);
        return !is_null($user->cashier_id)
            && $shift->isOpen();
    }

    public function preview($user): bool
    {
        return !is_null($user->cashier_id);
    }

    public function beReturned($user, Receipt $receipt): bool
    {
        $shift = Kaca::findShiftByCashierUser($user);
        return $receipt->wasSold()
            && Synchronization::isAvailable($receipt->id)
            && $shift->isOpen()
            && !Receipt::query()->where('related_receipt_id', '=', $receipt->id)->exists();
    }
}
