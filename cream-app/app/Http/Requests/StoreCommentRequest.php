<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
            // 'website' is a honeypot — handled silently in the controller, not here.
        ];
    }

    /** Whether the honeypot was tripped (a bot filled the hidden field). */
    public function honeypotTripped(): bool
    {
        return filled($this->input('website'));
    }
}
