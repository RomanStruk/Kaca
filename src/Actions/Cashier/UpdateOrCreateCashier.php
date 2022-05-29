<?php

declare(strict_types=1);

namespace Kaca\Actions\Cashier;

use Illuminate\Contracts\Auth\Authenticatable;
use Kaca\ActionRecorder;
use Kaca\CheckboxApiFacade;
use Kaca\Events\CashierAuthorized;
use Kaca\Models\Cashier;

class UpdateOrCreateCashier
{
    /**
     * @param string $accessToken
     * @param Authenticatable $authenticatable
     * @return Cashier
     */
    public function handle(string $accessToken, Authenticatable $authenticatable): Cashier
    {
        $api = CheckboxApiFacade::setBearerToken($accessToken);
        $response = $api->getCashierProfile();
        $cashier = app(SyncLocalCashier::class)->sync($accessToken, $response);

        event(new CashierAuthorized($cashier));

        if ($cashier->wasRecentlyCreated) {
            ActionRecorder::creating($authenticatable, Cashier::class, $cashier->id);
        } else {
            ActionRecorder::updating($authenticatable, Cashier::class, $cashier->id);
        }

        return $cashier;
    }
}
