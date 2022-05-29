<?php

declare(strict_types=1);

namespace Kaca\Actions\Shift;

use Illuminate\Support\Carbon;
use Kaca\Models\Shift;
use Kaca\Synchronization;
use function collect;
use function config;

class UpdateLocalShift
{
    public function update(Shift $shift, array $response): void
    {
        $data = collect($response)
            ->mapWithKeys(function ($item, $key) {
                if (is_null($item)){
                    return [$key => $item];
                }
                if ($key === 'opened_at' || $key === 'closed_at') {
                    return [$key => Carbon::parse($item)->setTimezone(config('app.timezone'))];
                }
                if ($key === 'cash_register') {
                    return ['cash_register_id' => $item['id']];
                }
                if ($key === 'balance') {
                    return ['balance' => $item['balance']];
                }
                return [$key => $item];
            })
            ->only(['serial', 'status', 'opened_at', 'closed_at', 'id', 'cash_register_id', 'balance'])
            ->toArray();

        $shift->fill($data);
        $shift->save();

        Synchronization::resolve($shift->status, $shift->id);

        $this->createReport($shift, $response);
    }

    protected function createReport(Shift $shift, array $response)
    {
        $report = collect($response['z_report'] ?? []);
        if ($report->isEmpty()) {
            return;
        }

        $shift->reports()->updateOrCreate(
            $report->only('id')->toArray(),
            $report->only(['id', 'serial', 'is_z_report'])->toArray()
        );
    }
}
