<?php

declare(strict_types=1);

namespace Kaca\Contracts;

use Kaca\Contracts\Helpers\Prices;
use Kaca\Contracts\Helpers\Quantities;

interface ReceiptGoods
{
    /**
     * Код товару
     */
    public function getCode(): string;

    /**
     * Назва товару
     */
    public function getName(): string;

    /**
     * Ціна товару в копійках
     */
    public function getPrice(): Prices;

    /**
     * Кількість товарів в чеку
     */
    public function getQuantity(): Quantities;
}
