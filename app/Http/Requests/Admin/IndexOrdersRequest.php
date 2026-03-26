<?php

namespace App\Http\Requests\Admin;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexOrdersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'fulfillment_type' => ['nullable', Rule::in(['all', ...array_keys(Order::fulfillmentLabels())])],
            'status' => ['nullable', Rule::in(['all', ...array_keys(Order::statusLabels())])],
            'payment_status' => ['nullable', Rule::in(['all', ...array_keys(Order::paymentStatusLabels())])],
            'search' => ['nullable', 'string', 'max:120'],
        ];
    }
}
