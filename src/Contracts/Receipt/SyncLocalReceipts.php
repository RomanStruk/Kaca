<?php

declare(strict_types=1);

namespace Kaca\Contracts\Receipt;

use Kaca\Models\Receipt;

interface SyncLocalReceipts
{
    public function sync(Receipt $receipt, array $response);
}
