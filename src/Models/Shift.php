<?php

declare(strict_types=1);

namespace Kaca\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Kaca\Helpers\Price;
use Kaca\Synchronization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'closed_at' => 'datetime',
        'opened_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        self::created(function (Shift $shift){
            Synchronization::init($shift->getUuid());
        });
    }

    public function synchronization(): HasOne
    {
        return $this->hasOne(\Kaca\Models\Synchronization::class, 'target')->withDefault(['status' => Synchronization::STATUS_DONE]);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(Cashier::class);
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function isOpen(): bool
    {
        return $this->status === 'OPENED';
    }

    public function isClosed(): bool
    {
        return $this->status === 'CLOSED';
    }

    public function getBalance(): Price
    {
        return new Price($this->balance, false);
    }

    public function getUuid(): string
    {
        return $this->id;
    }
}
