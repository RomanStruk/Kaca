<?php

declare(strict_types=1);

namespace Kaca\Actions\Cashier;

use Kaca\Models\Cashier;

class SyncLocalCashier
{
    public function sync(string $accessToken, array $attributes): Cashier
    {
        $attributes = collect($attributes)
            ->only(['id', 'full_name', 'nin', 'key_id', 'signature_type', 'created_at', 'certificate_end',])
            ->merge(['access_token' => $accessToken]);

        return Cashier::updateOrCreate(['id' => $attributes->get('id')], $attributes->toArray());
    }
}
