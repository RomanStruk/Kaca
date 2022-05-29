<?php

declare(strict_types=1);

namespace Kaca;

use Kaca\Contracts\CheckboxApi;
use Illuminate\Support\Facades\Facade;

class CheckboxApiFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CheckboxApi::class;
    }
}
