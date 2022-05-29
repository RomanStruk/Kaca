<?php

declare(strict_types=1);

namespace Kaca\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Kaca\Kaca;

class CashRegister extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'fiscal_number',
        'licence_key',
        'address',
        'title',
    ];

    protected $hidden = [
        'licence_key',
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Kaca::userModel());
    }

    public function shifts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Shift::class);
    }

    public function creator(): HasOne
    {
        return $this->hasOne(Action::class, 'target')
            ->where('tag', '=', class_basename($this).'Creating')
            ->withDefault();
    }

    public function getLicenceKey()
    {
        return $this->licence_key;
    }

    public function isTest(): bool
    {
        return Str::contains($this->fiscal_number, 'TEST');
    }

    public function getTitleAttribute()
    {
        if ($this->attributes['title'] === ''){
            return $this->attributes['fiscal_number'];
        }
        return $this->attributes['title'];
    }
}
