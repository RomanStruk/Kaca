<?php

declare(strict_types=1);

namespace Kaca\Contracts\Report;

use Kaca\Contracts\CheckboxExceptions;
use Kaca\Models\Cashier;
use Kaca\Models\Report;

interface CreatesXReports
{
    /**
     * Create X-Report
     *
     * @param Cashier $cashier
     * @throws CheckboxExceptions
     * @return Report
     */
    public function create(Cashier $cashier): Report;
}
