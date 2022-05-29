<?php

declare(strict_types=1);

namespace Kaca\Helpers;

use Kaca\Contracts\Helpers\Prices;

class Price implements Prices
{
    private int $price;

    public function __construct($price, bool $convertToKopeck = true)
    {
        $this->price = (int) ($convertToKopeck ? $price * 100 : $price);
    }

    /**
     * Ціна в копійках
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * Ціна в гривнях
     */
    public function getPriceInUAH(): float
    {
        return $this->price / 100;
    }

    /**
     * Ціна в національному форматі
     */
    public function getPriceInUAHFormat(): string
    {
        return number_format($this->getPriceInUAH(), 2, '.', ' ');
    }
}
