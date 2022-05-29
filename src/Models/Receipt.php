<?php

declare(strict_types=1);

namespace Kaca\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Kaca\ActionRecorder;
use Kaca\CheckboxApiFacade;
use Kaca\Contracts\Helpers\Prices;
use Kaca\Helpers\Price;
use Kaca\Synchronization;

class Receipt extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'fiscal_code',
        'type',
        'serial',
        'status',
        'delivery',
        'total_sum',
        'total_payment',
        'related_receipt_id',
        'order_id',
        'reverse_compatibility_data',
        'shift_id',
    ];

    protected $with = [
        'receiptGoods',
        'receiptPayments',
        'shift',
        'creator.user',
    ];

    protected $hidden = [
        'reverse_compatibility_data',
    ];

    protected $casts = [
        'delivery' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        self::created(function (Receipt $receipt){
            Synchronization::init($receipt->id);
        });
    }

    public function synchronization(): HasOne
    {
        return $this->hasOne(\Kaca\Models\Synchronization::class, 'target');
    }

    public function creator(): HasOne
    {
        return $this->hasOne(Action::class, 'target')
            ->where('tag', '=', class_basename($this) . ActionRecorder::CREATE)
            ->withDefault();
    }

    public function receiptGoods(): HasMany
    {
        return $this->hasMany(ReceiptGood::class);
    }

    public function receiptPayments(): HasMany
    {
        return $this->hasMany(ReceiptPayment::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function getTotalSum(): Prices
    {
        return $this->receiptGoods->getTotalSum();
    }

    public function getTotalPayment(): Prices
    {
        return new Price($this->receiptPayments->sum('value'), false);
    }

    public function wasRefunded(): bool
    {
        return $this->type === 'RETURN';
    }

    public function wasSold(): bool
    {
        return $this->type === 'SELL';
    }

    public function isValid(): bool
    {
        return $this->status === 'DONE';
    }

    public function getPath($fileType = 'pdf'): string
    {
        return CheckboxApiFacade::getReceiptPath($this->id, $fileType);
    }

    public function toCheckbox(): array
    {
        return [
            'id' => $this->id,
            'delivery' => $this->delivery,
            'payments' => $this->receiptPayments->toCheckbox(),
            'related_receipt_id' => $this->related_receipt_id,
            'goods' => $this->receiptGoods->toCheckbox(),
        ];
    }
}
