<?php

declare(strict_types=1);

namespace Kaca\Actions\Report;

use Kaca\CheckboxApiFacade;
use Kaca\Contracts\Report\CreatesXReports;
use Kaca\Models\Cashier;
use Kaca\Models\Report;

class CreateReport implements CreatesXReports
{
    /**
     * Create x report and save min info about it
     *
     * @param Cashier $cashier
     * @return Report
     * @throws \Kaca\Exception\CheckboxExceptions
     */
    public function create(Cashier $cashier): Report
    {
        $api = CheckboxApiFacade::setBearerToken($cashier->getAccessToken());
        $response = $api->createXReport();

        return app(StoreLocalReport::class)->store($cashier->getShift(), $response);
    }
}
