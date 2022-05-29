<?php

declare(strict_types=1);

namespace Kaca\Models;

use Illuminate\Database\Eloquent\Model;
use Kaca\Kaca;

class Action extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'tag',
        'user_id',
        'target',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(Kaca::$userModel, 'user_id');
    }

    /**
     * User name
     */
    public function getCreatorName(): string
    {
        if (is_null($this->user)) {
            return 'checkbox.ua';
        }
        return $this->user->{\Kaca\Kaca::$userFieldName};
    }
}
