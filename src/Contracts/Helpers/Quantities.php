<?php

declare(strict_types=1);

namespace Kaca\Contracts\Helpers;

interface Quantities
{
    /**
     * Кількість товарів на чеку
     * @return int
     */
    public function getFormatQuantity(): int;

    public function getQuantity(): int;
}
