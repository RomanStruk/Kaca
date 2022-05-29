<?php

namespace Kaca\Helpers;

use Illuminate\Database\Eloquent\Collection;

class ReceiptPaymentCollection extends Collection
{
    public function toCheckbox(): array
    {
        $payments = [];
        foreach ($this->items as $item) {
            $payments[] = $item->toCheckbox();
        }
        return $payments;
    }
}
