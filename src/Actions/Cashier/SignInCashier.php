<?php

declare(strict_types=1);

namespace Kaca\Actions\Cashier;

use Illuminate\Support\Facades\Validator;
use Kaca\CheckboxApiFacade;
use Kaca\Contracts\Cashier\SignInsCashiers;
use Kaca\Exception\CheckboxExceptions;
use Kaca\Models\Cashier;

class SignInCashier implements SignInsCashiers
{
    /**
     * Авторизація на сервісі по логіну і паролю
     *
     * @param array $credentials
     * @return Cashier
     * @throws CheckboxExceptions
     */
    public function signIn(array $credentials): string
    {
        $validated = Validator::make($credentials, [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ])->validate();

        $response = CheckboxApiFacade::signInCashier($validated);
        $accessToken = $response['access_token'] ?? '';
        if ($accessToken === '') {
            throw new CheckboxExceptions('Empty access token!');
        }

        return $accessToken;
    }
}
