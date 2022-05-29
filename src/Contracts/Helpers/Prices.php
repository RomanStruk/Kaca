<?php

declare(strict_types=1);

namespace Kaca\Contracts\Helpers;

interface Prices
{
    /**
     * Ціна в копійках
     */
    public function getPrice(): int;

    /**
     * Ціна в гривнях
     */
    public function getPriceInUAH(): float;

    /**
     * Ціна в національному форматі
     */
    public function getPriceInUAHFormat(): string;
}
