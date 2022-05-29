<?php

declare(strict_types=1);

namespace Kaca\Models;

use Kaca\Kaca;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cashier extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'full_name',
        'nin',
        'key_id',
        'signature_type',
        'created_at',
        'certificate_end',
        'access_token',
    ];

    protected $hidden = [
        'access_token',
    ];

    protected $dates = [
        'certificate_end',
    ];

    /**
     * Зміна касира на сьогодні
     *
     * @return HasOne
     */
    public function shift(): HasOne
    {
        return $this->hasOne(Shift::class)
            ->where('created_at', '>=', now()->startOfDay())
            ->latest()
            ->limit(1)
            ->withDefault(['status' => 'CLOSED']);
    }

    /**
     * Всі зміни касира
     *
     * @return HasMany
     */
    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    /**
     * Користувачі закріплені за касиром
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(Kaca::userModel());
    }

    /**
     * Отримання поточної зміни для касира
     *
     * @return Shift
     */
    public function getShift(): Shift
    {
        $this->load('shift');
        return $this->shift;
    }

    /**
     * Access token to checkbox.ua
     */
    public function getAccessToken(): ?string
    {
        return $this->access_token;
    }

    /**
     * Check signature
     */
    public function isTest(): bool
    {
        return $this->signature_type === 'TEST';
    }
}
