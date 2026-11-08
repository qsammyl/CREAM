<?php

namespace App\Http\Requests;

use App\Enums\EnquiryType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnquiryRequest extends FormRequest
{
    /** Request keys that are handled explicitly and never stored in `details`. */
    public const CONTROL_KEYS = [
        '_token', 'type', 'name', 'email', 'phone', 'country', 'message',
        'subject', 'asset_id', 'asset_ref', 'newsletter', 'botcheck',
        'cf-turnstile-response',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(EnquiryType::class)],
            'name' => ['required', 'string', 'max:160'],
            'email' => ['nullable', 'required_without:phone', 'email', 'max:255'],
            'phone' => ['nullable', 'required_without:email', 'string', 'max:60'],
            'country' => ['nullable', 'string', 'max:120'],
            'message' => ['nullable', 'string', 'max:4000'],
            'subject' => ['nullable', 'string', 'max:200'],
            'asset_id' => ['nullable', 'integer', 'exists:assets,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required_without' => 'Please give us an email address or a phone number so we can reply.',
            'phone.required_without' => 'Please give us a phone number or an email address so we can reply.',
        ];
    }

    /** Whether the honeypot was tripped (a bot ticked the hidden checkbox). */
    public function honeypotTripped(): bool
    {
        return filled($this->input('botcheck'));
    }

    /**
     * The type-specific fields, cleaned to short scalar strings.
     *
     * @return array<string, string>
     */
    public function details(): array
    {
        $details = [];

        foreach ($this->except(self::CONTROL_KEYS) as $key => $value) {
            if (is_array($value) || is_null($value) || $value === '') {
                continue;
            }
            // Keep only safe, short scalars — these are rendered, never executed.
            $details[(string) $key] = mb_substr(strip_tags((string) $value), 0, 500);
        }

        return $details;
    }
}
