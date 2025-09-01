<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Support\Facades\Auth;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        Fortify::authenticateUsing(function (Request $request) {
            // Validar los campos de email y password
            // con las reglas predeterminadas de Fortify.
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
                'captcha' => 'required',
            ], [
                'captcha.required' => 'El campo de verificación CAPTCHA es obligatorio.',
            ]);

            // Validar el captcha
            if (!captcha_check($request->input('captcha'))) {
                throw ValidationException::withMessages([
                    'captcha' => 'El código CAPTCHA ingresado no es válido.',
                ]);
            }

            // Aquí se intenta autenticar al usuario si las validaciones son exitosas
            if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
                return Auth::user();
            }

            return null;
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
