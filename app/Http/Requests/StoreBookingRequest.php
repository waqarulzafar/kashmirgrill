<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'booking_type' => $this->input('booking_type', Booking::TYPE_TABLE),
            'marketing_opt_in' => $this->boolean('marketing_opt_in'),
            'phone' => $this->input('phone') ?: $this->input('phone_display'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'website' => ['nullable', 'max:0'],
            'form_rendered_at' => ['required', 'integer'],
            'idempotency_key' => ['required', 'string', 'max:120'],
            'booking_type' => ['required', Rule::in([
                Booking::TYPE_TABLE,
                Booking::TYPE_EVENT,
            ])],
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:40'],
            'phone_country_iso2' => ['nullable', 'string', 'max:5'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'persons' => ['required', 'integer', 'min:1', 'max:300'],
            'selected_slot_id' => ['nullable', 'integer', 'exists:dine_in_slots,id'],
            'time_filter' => ['nullable', Rule::in(['all', 'lunch', 'dinner'])],
            'time' => ['nullable', 'date_format:H:i'],
            'table_preference' => ['nullable', 'string', 'max:120'],
            'selected_menu' => ['nullable', 'string', 'max:120'],
            'special_occasion' => ['nullable', 'string', 'max:120'],
            'payment_method' => ['required', Rule::in([
                Booking::PAYMENT_METHOD_PAY_ON_ARRIVAL,
                Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION,
            ])],
            'additional_notes' => ['nullable', 'string', 'max:1200'],
            'marketing_opt_in' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'selected_slot_id.required' => 'Please choose one available time slot.',
            'time.required' => 'Please pick your preferred event start time.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->sometimes('selected_slot_id', ['required', 'integer', 'exists:dine_in_slots,id'], function (): bool {
            return $this->input('booking_type') === Booking::TYPE_TABLE;
        });

        $validator->sometimes('time', ['required', 'date_format:H:i'], function (): bool {
            return $this->input('booking_type') === Booking::TYPE_EVENT;
        });
    }
}
