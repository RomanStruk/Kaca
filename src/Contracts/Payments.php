<?php

declare(strict_types=1);

namespace Kaca\Contracts;

use Kaca\Contracts\Helpers\Prices;

interface Payments
{
    public function getPaymentType(): string;
    public function getPaymentValue(): Prices;
    public function getPaymentLabel(): string;
}
