<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:255'],
            'role' => ['nullable', 'string', 'max:120'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'body' => ['required', 'string', 'min:10', 'max:2000'],
            // "consent" is the publish-consent checkbox; absence is allowed
            // (the feedback is still stored privately, just never publishable).
            'consent' => ['nullable', 'boolean'],
            // 'website' is a honeypot — handled silently in the controller.
        ];
    }

    public function messages(): array
    {
        return [
            'body.min' => 'Please share a little more so we can learn from your experience.',
            'rating.required' => 'Please choose a star rating.',
        ];
    }

    /** Whether the honeypot was tripped (a bot filled the hidden field). */
    public function honeypotTripped(): bool
    {
        return filled($this->input('website'));
    }
}
