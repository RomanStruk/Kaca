<?php

namespace Kaca\Tests\stubs;

use Kaca\Kaca;
use Kaca\KacaApplicationServiceProvider as BaseKacaApplicationServiceProvider;
use Kaca\Tests\TestModels\User;

class KacaApplicationServiceProvider extends BaseKacaApplicationServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        Kaca::useUserModel(User::class);
    }
}
