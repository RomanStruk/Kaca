<?php

declare(strict_types=1);

namespace Kaca\Contracts;

interface SyncStatuses
{
    public function sync(): void;
}
