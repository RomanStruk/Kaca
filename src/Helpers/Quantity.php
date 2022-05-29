<?php

declare(strict_types=1);

namespace Kaca\Helpers;

use Kaca\Contracts\Helpers\Quantities;

class Quantity implements Quantities
{
    protected int $quantity;

    public function __construct(int $quantity, $convert = true)
    {
        $this->quantity = $convert ? $quantity * 1000 : $quantity;
    }

    public function getFormatQuantity(): int
    {
        return $this->quantity;
    }

    public function getQuantity(): int
    {
        return $this->quantity / 1000;
    }
}
