<?php

declare(strict_types=1);

namespace Kaca\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreateReceiptRequest extends FormRequest
{
    /**
     * Дозвіл на валідацію
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'id' => ['nullable', 'uuid'],

            'goods' => ['required', 'array', 'min:1'],
            'goods.*.code' => ['required', 'string',],
            'goods.*.name' => ['required', 'string', 'min:1',],
            'goods.*.quantity' => ['required', 'numeric', 'min:1',],
            'goods.*.price' => ['required', 'numeric', 'min:0.01',],

            'deliveries' => ['required', 'array', 'min:1'],
            'deliveries.emails' => ['required', 'array', 'min:1'],
            'deliveries.emails.*' => ['required', 'email'],

            'payments' => ['nullable', 'array', 'min:1'],
            'payments.*.type' => ['required', 'string', 'in:CASHLESS,CASH',],
            'payments.*.value' => ['required', 'numeric', 'min:0.01',],
            'payments.*.label' => ['required', 'string', 'min:3',],

            'reverse_compatibility_data' => ['nullable', 'string'],
            'order_id' => ['nullable', 'string'],
        ];
    }

    /**
     * Validation error messages
     */
    public function messages(): array
    {
        return [
            'required' => 'Поле :attribute є обов`язковим.',
            'string' => 'Поле :attribute має бути строкою.',
        ];
    }
}
