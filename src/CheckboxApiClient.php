<?php

declare(strict_types=1);

namespace Kaca;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Kaca\Contracts\CheckboxApi;
use Kaca\Contracts\CheckboxEntries;
use Kaca\Exception\CheckboxBadRequestException;
use Kaca\Exception\CheckboxExceptions;
use Kaca\Exception\CheckboxInvalidCredentialsException;
use Kaca\Exception\CheckboxValidationException;

class CheckboxApiClient implements CheckboxApi
{
    protected array $config;

    protected PendingRequest $pendingRequest;

    public function __construct(array $config)
    {
        $this->pendingRequest = Http::baseUrl($config['base_url'])
            ->acceptJson()
            ->withoutVerifying();
        $this->config = $config;
    }

    /**
     * Встановити ключ ліцензії каси для заголовку
     *
     * @param string $licenceKey
     * @return CheckboxApi
     */
    public function withLicenseKey(string $licenceKey): CheckboxApi
    {
        $this->pendingRequest->withHeaders([
            'X-License-Key' => $licenceKey,
        ]);
        return $this;
    }

    /**
     * Авторизація запиту
     *
     * @param string $token
     * @return CheckboxApi
     */
    public function setBearerToken(string $token): CheckboxApi
    {
        $this->pendingRequest->withToken($token);
        return $this;
    }

    /**
     * Формування і відправка запиту на сервіс
     *
     * @param string $method
     * @param string $uri
     * @param array $body
     * @return array
     * @throws Contracts\CheckboxExceptions
     */
    public function request(string $method, string $uri, array $body): array
    {
        $payload = array_merge($this->pendingRequest->getOptions(), compact('method', 'uri', 'body'));
        $this->checkboxEntry()->createRecord('request', $payload); //payload
        if ($method === 'post'){
            $response = $this->pendingRequest->post($uri, $body);
        }else{
            $response = $this->pendingRequest->get($uri, $body);
        }

        if ($response->header('Content-Type') === 'text/plain; charset=utf-8'){
            $json = [$response->body(),];
        }else{
            $json = $response->json();
        }

        $this->checkboxEntry()->createRecord('response', $json);

        $this->validateResponseStatus($json, $response->status());

        return $json;
    }

    /**
     * Валідація відповіді від сервісу checkbox
     *
     * @param array|null $json
     * @param int $status
     * @throws CheckboxBadRequestException
     * @throws CheckboxExceptions
     * @throws CheckboxInvalidCredentialsException
     * @throws CheckboxValidationException
     */
    private function validateResponseStatus(?array $json, int $status): void
    {
        if (empty($json)) {
            throw new CheckboxExceptions(__('Запит вернув пустий результат'));
        }

        switch ($status) {
            case 400: // Bad Request
                throw new CheckboxBadRequestException($json['message']?? 'Bad Request');
            case 403: // invalid credentials
                throw new CheckboxInvalidCredentialsException($json['message'] ?? 'Invalid Credentials');
            case 422: // validation
                throw new CheckboxValidationException($json['message'] ?? 'Validation errors');
        }

        if (!empty($json['message'])) {
            throw new CheckboxExceptions($json['message']);
        }
    }

    /**
     * Get receipt
     *
     * @throws Contracts\CheckboxExceptions
     */
    public function getReceipt(string $uuid): array
    {
        $this->checkboxEntry()->setTag('receipt:' . $uuid);

        return $this->request('get', '/api/v1/receipts/' . $uuid, []);
    }

    /**
     * Get receipt path as file
     *
     * @param string $uuid
     * @param string $type
     * @return string
     */
    public function getReceiptPath(string $uuid, string $type = 'pdf'): string
    {
        return $this->config['base_url'] . '/api/v1/receipts/' . $uuid . '/' . $type;
    }

    /**
     * Handle an incoming receipt DTO.
     *
     * @throws Contracts\CheckboxExceptions
     */
    public function createReceipt(array $body): array
    {
        $this->checkboxEntry()->setTag('receipt:' . $body['id']);

        return $this->request('post', '/api/v1/receipts/sell', $body);
    }

    /**
     * Handle an incoming shift id.
     *
     * @throws Contracts\CheckboxExceptions
     */
    public function createShift(string $uuid): array
    {
        $this->checkboxEntry()->setTag('shift:' . $uuid);

        return $this->request('post', '/api/v1/shifts', ['id' => $uuid]);
    }

    /**
     * Handle a closing shift.
     *
     * @throws Contracts\CheckboxExceptions
     */
    public function closeShift(string $uuid): array
    {
        $this->checkboxEntry()->setTag('shift:' . $uuid);

        return $this->request('post', '/api/v1/shifts/close', []);
    }

    /**
     * @param string $uuid
     * @return array
     * @throws Contracts\CheckboxExceptions
     */
    public function getShift(string $uuid): array
    {
        $this->checkboxEntry()->setTag('shift:' . $uuid);

        return $this->request('get', '/api/v1/shifts/' . $uuid, []);
    }

    /**
     * @return array
     * @throws Contracts\CheckboxExceptions
     */
    public function getCashRegisterInfo(): array
    {
        $this->checkboxEntry()->setTag('cash-register');

        return $this->request('get', '/api/v1/cash-registers/info', []);
    }

    /**
     * @param string $uuid
     * @return array
     * @throws Contracts\CheckboxExceptions
     */
    public function getCashRegisterInfoByUuid(string $uuid): array
    {
        $this->checkboxEntry()->setTag('cash-register:' . $uuid);

        return $this->request('get', '/api/v1/cash-registers/' . $uuid, []);
    }

    /**
     * @return array
     * @throws Contracts\CheckboxExceptions
     */
    public function getCashierProfile(): array
    {
        $this->checkboxEntry()->setTag('cashier');

        return $this->request('get', '/api/v1/cashier/me', []);
    }

    /**
     * @return array
     * @throws Contracts\CheckboxExceptions
     */
    public function getCashierShift(): array
    {
        $this->checkboxEntry()->setTag('cashier');

        return $this->request('get', '/api/v1/cashier/shift', []);
    }

    /**
     * Auth
     *
     * @param array $credential
     * @return array
     * @throws Contracts\CheckboxExceptions
     */
    public function signInCashier(array $credential): array
    {
        $this->checkboxEntry()->setTag('cashier');

        return $this->request('post', '/api/v1/cashier/signin', $credential);
    }

    /**
     * Create X Report
     *
     * @return array
     * @throws Contracts\CheckboxExceptions
     */
    public function createXReport(): array
    {
        $this->checkboxEntry()->setTag('report');

        return $this->request('post', '/api/v1/reports', []);
    }

    /**
     * Get Report By UUID
     *
     * @param string $uuid
     * @return array
     * @throws Contracts\CheckboxExceptions
     */
    public function getReport(string $uuid): array
    {
        $this->checkboxEntry()->setTag('report:' . $uuid);

        return $this->request('get', '/api/v1/reports/' . $uuid, []);
    }

    /**
     * Get Report By UUID as text
     *
     * @param string $uuid
     * @return array
     * @throws Contracts\CheckboxExceptions
     */
    public function getReportText(string $uuid): array
    {
        $this->checkboxEntry()->setTag('report:' . $uuid);

        return $this->request('get', '/api/v1/reports/' . $uuid . '/text', []);
    }

    /**
     * Get Webhook
     *
     * @return array
     * @throws Contracts\CheckboxExceptions
     */
    public function getWebHook(): array
    {
        $this->checkboxEntry()->setTag('webhook');

        return $this->request('get', '/api/v1/webhook', []);
    }

    /**
     * Логування запитів до сервісу
     *
     * @return CheckboxEntries
     */
    protected function checkboxEntry(): CheckboxEntries
    {
        return app(CheckboxEntries::class);
    }
}
