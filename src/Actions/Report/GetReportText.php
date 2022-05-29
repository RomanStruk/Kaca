<?php

declare(strict_types=1);

namespace Kaca\Actions\Report;

use Kaca\CheckboxApiFacade;
use Kaca\Models\Cashier;
use Kaca\Models\Report;

class GetReportText
{
    public function get(Cashier $cashier, Report $report): string
    {
        $api = CheckboxApiFacade::setBearerToken($cashier->getAccessToken());
        $text = $api->getReportText($report->id);

        return array_shift($text);
    }
}
