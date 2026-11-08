<?php

namespace App\Console\Commands;

use App\Enums\Role;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateAdmin extends Command
{
    protected $signature = 'cream:create-admin';

    protected $description = 'Create (or promote) a CREAM admin account. Prompts for name, email and password in the terminal — no credentials are hard-coded.';

    public function handle(): int
    {
        $name = text('Full name', required: true);
        $email = text('Email address', required: true);
        $pw = password('Password (min 12 chars, checked against known breaches)', required: true);
        $pwConfirm = password('Confirm password', required: true);

        $validator = Validator::make(
            [
                'name' => $name,
                'email' => $email,
                'password' => $pw,
                'password_confirmation' => $pwConfirm,
            ],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', 'confirmed', Password::min(12)->uncompromised()],
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $email = strtolower(trim($email));

        if ($existing = User::where('email', $email)->first()) {
            if (! $this->confirm("A user with {$email} already exists. Promote to admin and reset their password?", false)) {
                $this->warn('Aborted. No changes made.');

                return self::FAILURE;
            }

            $existing->forceFill([
                'name' => $name,
                'role' => Role::Admin,
                'is_active' => true,
                'password' => $pw, // 'hashed' cast hashes on save
            ])->save();

            ActivityLogger::log('update', $existing, properties: ['via' => 'cream:create-admin'], actor: $existing);
            $this->info("Updated {$email} as an active admin.");

            return self::SUCCESS;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $pw, // 'hashed' cast hashes on save
            'role' => Role::Admin,
            'is_active' => true,
        ]);

        ActivityLogger::log('create', $user, properties: ['via' => 'cream:create-admin'], actor: $user);

        $this->info("Admin {$email} created.");
        $this->line('Two-factor authentication is required for admins — set it up from your profile after first login.');

        return self::SUCCESS;
    }
}
