<?php

namespace Kaca\Helpers;

use Illuminate\Database\Eloquent\Collection;

class ReceiptGoodCollection extends Collection
{
    public function toCheckbox(): array
    {
        $goods = [];
        foreach ($this->items as $item) {
            $goods[] = $item->toCheckbox();
        }
        return $goods;
    }

    public function getTotalSum(): Price
    {
        $totalPrice = 0;
        foreach ($this->items as $item) {
            $totalPrice += $item->getQuantity()->getQuantity() * $item->getPrice()->getPrice();
        }
        return new Price($totalPrice, false);
    }
}
