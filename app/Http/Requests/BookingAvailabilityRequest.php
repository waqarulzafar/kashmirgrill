<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingAvailabilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date', 'after_or_equal:today'],
            'guest_count' => ['nullable', 'integer', 'min:1', 'max:300'],
            'time_filter' => ['nullable', Rule::in(['all', 'lunch', 'dinner'])],
            'booking_type' => ['nullable', Rule::in([Booking::TYPE_TABLE, Booking::TYPE_EVENT])],
        ];
    }
}
