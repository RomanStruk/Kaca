<?php

declare(strict_types=1);

namespace Kaca;

use Kaca\Models\Synchronization as SynchronizationModel;

class Synchronization
{
    public const STATUS_CREATED = 'CREATED';        // очікує синхронізацію
    public const STATUS_PROCESSING = 'PROCESSING';  // синхронізація
    public const STATUS_DONE = 'DONE';              // успішна синхронізація (чек створений або відмовлено в створенні)
    public const STATUS_FAILED = 'ERROR';           // невдала синхронізація

    public static function init(string $id): void
    {
        SynchronizationModel::query()->create([
            'target' => $id,
            'status' => self::STATUS_CREATED,
        ]);
    }

    public static function begin(string $target): void
    {
        SynchronizationModel::query()->where(['target' => $target,])->update([
            'status' => self::STATUS_PROCESSING,
        ]);
    }

    public static function finish(string $target): void
    {
        SynchronizationModel::query()->where(['target' => $target,])->update([
            'status' => self::STATUS_DONE,
        ]);
    }

    public static function failed(string $target): void
    {
        SynchronizationModel::query()->where(['target' => $target,])->update([
            'status' => self::STATUS_FAILED,
        ]);
    }

    public static function resolve(string $status, string $target): void
    {
        if ($status === 'CREATED' || $status === 'OPENING' || $status === 'CLOSING') {
            SynchronizationModel::query()->where(['target' => $target,])->update([
                'status' => self::STATUS_CREATED,
            ]);
        } elseif ($status === 'OPENED' || $status === 'CLOSED' || $status === 'DONE') {
            self::finish($target);
        } elseif ($status === 'ERROR') {
            self::finish($target);
        }
    }

    public static function isAvailable(string $target): bool
    {
        return SynchronizationModel::query()->where([
            'target' => $target,
            'status' => self::STATUS_DONE,
        ])->exists();
    }

    /**
     * Отримати статус синхронізації для моделі
     */
    public static function getStatusFor(string $target): string
    {
        return SynchronizationModel::query()
            ->firstOrCreate(['target' => $target,], ['status' => self::STATUS_DONE,])
            ->getAttribute('status');
    }

    /**
     * Отримати всі елементи які підпадають під заданий статус синхронізації
     */
    public static function findWithStatus(string $class, string $status): \Illuminate\Support\Collection
    {
        SynchronizationModel::resolveRelationUsing(
            'targets',
            function (SynchronizationModel $synchronization) use ($class) {
                return $synchronization->hasMany($class, 'id', 'target');
            }
        );
        return SynchronizationModel::query()
            ->whereHas('targets')
            ->where('status', '=', $status)
            ->with('targets')
            ->get()
            ->pluck('targets')
            ->collapse();
    }
}
