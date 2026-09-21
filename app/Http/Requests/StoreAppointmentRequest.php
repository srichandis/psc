<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Anyone may submit a booking request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],

            // The specialty is stored on the appointment as a snapshot so the
            // request survives the specialty being renamed or removed.
            'specialty' => ['required', 'string', Rule::exists('specialties', 'name')],
            // Only specialists currently shown on the website may be booked, so
            // a hidden profile cannot be selected by tampering with the form.
            'specialist_id' => ['nullable', 'integer', Rule::exists('specialists', 'id')->where('is_active', true)],

            'referral_status' => ['required', Rule::in(array_keys(Appointment::REFERRAL_STATUSES))],

            'preferred_date' => ['nullable', 'date', 'after_or_equal:today'],
            'preferred_time' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'full_name' => 'full name',
            'referral_status' => 'referral status',
            'preferred_date' => 'preferred date',
            'preferred_time' => 'preferred time',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'specialty.exists' => 'Please choose a specialty from the list.',
            'preferred_date.after_or_equal' => 'Please choose today or a future date.',
        ];
    }
}
