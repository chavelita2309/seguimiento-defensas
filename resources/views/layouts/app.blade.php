<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema Web de Seguimiento y Control de Modalidades de Graduación - Ingeniería Textil U.P.E.A.') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased flex flex-col min-h-screen">

    {{-- Banner de notificaciones --}}
    <x-banner />

    {{-- Header / Menú de navegación --}}
    @livewire('navigation-menu')

    {{-- Encabezado fijo con el nombre del sistema --}}
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-4 px-6 text-center font-semibold text-lg text-gray-700">
            SISMOD - Sistema Web de Seguimiento y Control de Modalidades de Graduación - Ingeniería Textil U.P.E.A.
        </div>
    </header>

    {{-- Contenido principal --}}
    <div class="flex-grow bg-gray-100">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{-- Mensajes globales --}}
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-800 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Contenido dinámico --}}
            {{ $slot }}
        </main>
    </div>


    {{-- Footer global --}}
    <footer class="bg-white border-t py-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} SISMOD - Sistema Web de Seguimiento y Control de Modalidades de Graduación - Ingeniería Textil U.P.E.A.
        Todos los derechos reservados.<br>
         <small class="text-xs italic text-gray-400">
            Desarrolladora: Isabel Y. Cañaviri Robles
        </small>
    </footer>

    @stack('modals')
    @livewireScripts
</body>
</html>


