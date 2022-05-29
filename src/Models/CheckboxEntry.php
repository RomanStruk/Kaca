<?php

declare(strict_types=1);

namespace Kaca\Models;

use Illuminate\Database\Eloquent\Model;

class CheckboxEntry extends Model
{
    public const UPDATED_AT = null;

    protected $guarded = [];

    protected $casts = [
        'content' => 'json',
    ];
}
