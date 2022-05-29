<?php

declare(strict_types=1);

namespace Kaca\Actions\Report;

use Kaca\Models\Report;
use Kaca\Models\Shift;

class StoreLocalReport
{
    public function store(Shift $shift, array $attributes): Report
    {
        $report = new Report();
        $report->fill(
            collect($attributes)->only([
                'id',
                'serial',
                'is_z_report',
            ])->toArray()
        );
        $shift->reports()->save($report);

        return $report;
    }
}
