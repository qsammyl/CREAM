<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * At least 12 characters and checked against known breached passwords
     * (via Laravel's uncompromised() / Have I Been Pwned k-anonymity API).
     *
     * @return array<int, Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        return [
            'required',
            'string',
            Password::min(12)->uncompromised(),
            'confirmed',
        ];
    }
}
