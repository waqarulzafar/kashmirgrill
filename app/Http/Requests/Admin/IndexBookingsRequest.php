<?php

namespace App\Http\Requests\Admin;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexBookingsRequest extends FormRequest
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
            'booking_type' => ['nullable', Rule::in(['all', ...array_keys(Booking::typeLabels())])],
            'status' => ['nullable', Rule::in(['all', ...array_keys(Booking::statusLabels())])],
            'payment_status' => ['nullable', Rule::in(['all', ...array_keys(Booking::paymentStatusLabels())])],
            'search' => ['nullable', 'string', 'max:120'],
        ];
    }
}
