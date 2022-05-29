<?php

declare(strict_types=1);

namespace Kaca\Contracts;

interface CheckboxApi
{
    /**
     * Встановити ключ ліцензії каси для заголовку
     */
    public function withLicenseKey(string $licenceKey): CheckboxApi;

    /**
     * Авторизація запиту
     */
    public function setBearerToken(string $token): CheckboxApi;

    /**
     * Send request
     *
     * @throws CheckboxExceptions
     */
    public function request(string $method, string $uri, array $body): array;
}
