<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\Turnstile;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        // Hand-built Blade views (brand styling), served under /staff/*.
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));
        Fortify::resetPasswordView(fn (Request $request) => view('auth.reset-password', ['request' => $request]));
        Fortify::confirmPasswordView(fn () => view('auth.confirm-password'));
        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor-challenge'));

        // Credentials + Cloudflare Turnstile + active-account check.
        // A generic failure (return null) avoids leaking which part failed.
        Fortify::authenticateUsing(function (Request $request) {
            if (! app(Turnstile::class)->verify($request->input('cf-turnstile-response'), $request->ip())) {
                throw ValidationException::withMessages([
                    Fortify::username() => __('Please complete the verification challenge and try again.'),
                ]);
            }

            $user = User::where('email', $request->input(Fortify::username()))->first();

            if ($user
                && $user->is_active
                && Hash::check((string) $request->input('password'), $user->password)) {
                return $user;
            }

            return null;
        });

        $this->configureRateLimiting();
        $this->registerAuthEventListeners();
    }

    protected function configureRateLimiting(): void
    {
        // Throttle logins per-email AND per-IP (security requirement).
        RateLimiter::for('login', function (Request $request) {
            $email = Str::transliterate(Str::lower((string) $request->input(Fortify::username())));

            return [
                Limit::perMinute(5)->by('email:'.$email),
                Limit::perMinute(20)->by('ip:'.$request->ip()),
            ];
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }

    protected function registerAuthEventListeners(): void
    {
        Event::listen(Login::class, function (Login $event) {
            if ($event->user instanceof User) {
                $event->user->forceFill(['last_login_at' => now()])->saveQuietly();
                ActivityLogger::log('login', $event->user, request(), actor: $event->user);
            }
        });

        Event::listen(Failed::class, function (Failed $event) {
            ActivityLogger::logFailedLogin((string) ($event->credentials['email'] ?? ''), request());
        });

        Event::listen(Logout::class, function (Logout $event) {
            if ($event->user instanceof User) {
                ActivityLogger::log('logout', $event->user, request(), actor: $event->user);
            }
        });

        Event::listen(Lockout::class, function () {
            ActivityLogger::logLockout(request());
        });
    }
}
