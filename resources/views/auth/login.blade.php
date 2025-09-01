<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
          

            <a href="/">
                <img src="{{ asset('logo.png') }}" alt="Logo de la empresa"
                    class="align-center w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Correo electrónico') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Contraseña') }}" />
                <div class="relative">
                    <x-input id="password" class="block mt-1 w-full pr-10" type="password" name="password" required
                        autocomplete="current-password" />


                    <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3"
                        onclick="togglePasswordVisibility()">

                        <svg id="eye-open" class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: block;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>

                        <svg id="eye-closed" class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.25A10.024 10.024 0 0015.75 12c-1.416-2.91-4.24-5.04-7.875-5.04s-6.459 2.13-7.875 5.04a10.025 10.025 0 001.875 6.25M12 17.25c2.485 0 4.5-2.015 4.5-4.5S14.485 8.25 12 8.25 7.5 10.265 7.5 12s2.015 4.5 4.5 4.5zM12 1.5l3.75 3.75M12 15.75L8.25 12M12 1.5V6" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <x-label for="captcha">Captcha</x-label>
                <div class="flex items-center space-x-2">
                    <img src="{{ captcha_src('clasic') }}" alt="captcha" class="rounded shadow" />
                    <x-input type="text" name="captcha" class="form-input" placeholder="Ingrese el texto" />
                </div>
                @error('captcha')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Recuérdame') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('password.request') }}">
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif
                <x-button class="ms-4">
                    {{ __('Iniciar sesión') }}
                </x-button>
            </div>
        </form>


        <script>
            function togglePasswordVisibility() {
                const passwordInput = document.getElementById('password');
                const eyeOpenIcon = document.getElementById('eye-open');
                const eyeClosedIcon = document.getElementById('eye-closed');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeOpenIcon.style.display = 'none';
                    eyeClosedIcon.style.display = 'block';
                } else {
                    passwordInput.type = 'password';
                    eyeOpenIcon.style.display = 'block';
                    eyeClosedIcon.style.display = 'none';
                }
            }
        </script>
    </x-authentication-card>
</x-guest-layout>
