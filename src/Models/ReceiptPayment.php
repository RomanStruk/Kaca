<?php

namespace Kaca\Models;

use Illuminate\Database\Eloquent\Model;
use Kaca\Contracts\Helpers\Prices;
use Kaca\Contracts\Payments;
use Kaca\Helpers\Price;
use Kaca\Helpers\ReceiptPaymentCollection;

class ReceiptPayment extends Model implements Payments
{
    public $timestamps = false;

    protected $fillable = [
        'receipt_id',
        'value',
        'type',
        'label',
    ];

    public function setValueAttribute($value)
    {
        if ($value instanceof Price){
            $this->attributes['value'] = $value->getPrice();
        }else{
            $this->attributes['value'] = (new Price($value))->getPrice();
        }
    }

    public function getPaymentType(): string
    {
        return $this->type;
    }

    public function getPaymentValue(): Prices
    {
        return new Price($this->value, false);
    }

    public function getPaymentLabel(): string
    {
        return $this->label;
    }


    public function newCollection(array $models = []): ReceiptPaymentCollection
    {
        return new ReceiptPaymentCollection($models);
    }

    public function toCheckbox(): array
    {
        return [
            'type' => $this->getPaymentType(),
            'value' => $this->getPaymentValue()->getPrice(),
            'label' => $this->getPaymentLabel(),
        ];
    }
}
