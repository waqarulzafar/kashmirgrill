<?php

namespace App\Http\Requests\Admin;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class UpdateBookingRequest extends FormRequest
{
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
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'booking_type' => ['required', Rule::in(array_keys(Booking::typeLabels()))],
            'status' => ['required', Rule::in(array_keys(Booking::statusLabels()))],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'dine_in_slot_id' => ['nullable', 'integer', 'exists:dine_in_slots,id'],
            'persons' => ['required', 'integer', 'min:1', 'max:500'],
            'table_preference' => ['nullable', 'string', 'max:255'],
            'selected_menu' => ['nullable', 'string', 'max:255'],
            'special_occasion' => ['nullable', 'string', 'max:120'],
            'payment_method' => ['nullable', Rule::in(array_keys(Booking::paymentMethodLabels()))],
            'payment_status' => ['required', Rule::in(array_keys(Booking::paymentStatusLabels()))],
            'payment_amount' => [
                Rule::requiredIf(fn (): bool => $this->input('payment_method') === Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION),
                'nullable',
                'numeric',
                'min:0.5',
            ],
            'marketing_opt_in' => ['nullable', 'boolean'],
            'additional_notes' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $booking = $this->route('booking');

        if (! $booking instanceof Booking) {
            return;
        }

        $this->merge([
            'full_name' => $this->input('full_name', $booking->full_name),
            'email' => $this->input('email', $booking->email),
            'phone' => $this->input('phone', $booking->phone),
            'booking_type' => $this->input('booking_type', $booking->booking_type),
            'status' => $this->input('status', $booking->status),
            'date' => $this->input('date', optional($booking->date)->format('Y-m-d')),
            'time' => $this->input('time', Carbon::parse($booking->time)->format('H:i')),
            'dine_in_slot_id' => $this->input('dine_in_slot_id', $booking->dine_in_slot_id),
            'persons' => $this->input('persons', $booking->persons),
            'table_preference' => $this->input('table_preference', $booking->table_preference),
            'selected_menu' => $this->input('selected_menu', $booking->selected_menu),
            'special_occasion' => $this->input('special_occasion', $booking->special_occasion),
            'payment_method' => $this->input('payment_method', $booking->payment_method),
            'payment_status' => $this->input('payment_status', $booking->payment_status),
            'payment_amount' => $this->input('payment_amount', $booking->payment_amount),
            'marketing_opt_in' => $this->input('marketing_opt_in', $booking->marketing_opt_in),
            'additional_notes' => $this->input('additional_notes', $booking->additional_notes),
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Guest name is required.',
            'email.required' => 'Guest email is required.',
            'email.email' => 'Enter a valid guest email address.',
            'phone.required' => 'Guest phone number is required.',
            'booking_type.required' => 'Choose a booking type.',
            'status.required' => 'Choose a booking status.',
            'date.required' => 'Choose a booking date.',
            'time.required' => 'Choose a booking time.',
            'time.date_format' => 'Use a valid booking time.',
            'dine_in_slot_id.exists' => 'Choose a valid dine-in slot.',
            'persons.required' => 'Enter the number of guests.',
            'persons.min' => 'A booking must include at least one guest.',
            'payment_status.required' => 'Choose a payment status.',
            'payment_amount.required' => 'Enter the Stripe payment amount for this booking.',
            'payment_amount.min' => 'The booking payment amount must be at least 0.50.',
        ];
    }
}
