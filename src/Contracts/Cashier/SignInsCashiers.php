<?php

declare(strict_types=1);

namespace Kaca\Contracts\Cashier;

use GuzzleHttp\Exception\GuzzleException;
use Kaca\Contracts\CheckboxExceptions;
use Kaca\Models\Cashier;

interface SignInsCashiers
{
    /**
     * Авторизація на сервісі по логіну і паролю
     *
     * @param array $credentials
     * @return Cashier
     * @throws CheckboxExceptions
     * @throws GuzzleException
     */
    public function signIn(array $credentials): string;
}
