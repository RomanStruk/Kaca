<?php

declare(strict_types=1);

namespace Kaca\Models;

use Illuminate\Database\Eloquent\Model;
use Kaca\Contracts\Helpers\Prices;
use Kaca\Contracts\Helpers\Quantities;
use Kaca\Contracts\ReceiptGoods;
use Kaca\Helpers\Price;
use Kaca\Helpers\Quantity;
use Kaca\Helpers\ReceiptGoodCollection;

class ReceiptGood extends Model implements ReceiptGoods
{
    protected $fillable = [
        'id',
        'name', // назва товара\послуги
        'code', // код товара\послуги
        'price', // ціна товара\послуги, вказується в копійках.Наприклад ціна 454 грн, передається 45400
        'quantity', //кількість
        'is_return', // повернення
        'related_local_good_id',
        'receipt_id',
    ];

    protected $casts = [
        'quantity' => 'int',
        'price' => 'int',
        'is_return' => 'bool',
    ];

    public function setQuantityAttribute($value)
    {
        if ($value instanceof Quantity){
            $this->attributes['quantity'] = $value->getQuantity();
        }else{
            $this->attributes['quantity'] = (new Quantity(intval($value)))->getFormatQuantity();
        }
    }

    public function setPriceAttribute($value)
    {
        if ($value instanceof Price){
            $this->attributes['price'] = $value->getPrice();
        }else{
            $this->attributes['price'] = (new Price($value))->getPrice();
        }
    }

    public function getPrice(): Prices
    {
        return new Price($this->price, false);
    }

    public function getQuantity(): Quantities
    {
        return new Quantity((int) $this->quantity, false);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return (string) $this->id;
    }

    public function newCollection(array $models = [])
    {
        return new ReceiptGoodCollection($models);
    }

    public function toCheckbox(): array
    {
        return [
            'good' => [
                'code' => $this->getCode(),
                'name' => $this->getName(),
                'price' => $this->getPrice()->getPrice(),
            ],
            'quantity' => $this->getQuantity()->getFormatQuantity(),
            'is_return' => $this->is_return,
        ];
    }
}
