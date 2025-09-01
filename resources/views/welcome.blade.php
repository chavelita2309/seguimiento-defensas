<!-- resources/views/welcome.blade.php -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>SISMOD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50 text-gray-800 font-sans">
    <!-- Header -->
    {{-- <header class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            
            @auth
                <a href="{{ url('/dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Panel</a>
            @else
                <div class="space-x-12">
                    <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Iniciar sesión</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Registrarse</a>
                    @endif
                </div>
            @endauth
        </div>
    </header> --}}

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-900 to-red-800 text-white py-20 relative">
    <div class="absolute top-10 left-10">
        <img src="/logo_upea.png" alt="Logo UPEA" class="h-36" />
    </div>
    <div class="absolute top-10 right-10">
        <img src="/logo.png" alt="Logo" class="h-36" />
    </div>
    <div class="max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold mb-4">SISMOD - Sistema Web de Seguimiento y Control de Modalidades de Graduación </h2>
        <h1 class="text-4xl font-bold mb-4"> Ingeniería Textil U.P.E.A. </h1><br><br>
        <p class="text-lg mb-6">Supervisa y gestiona el avance del proceso de titulación desde la aprobación del perfil de grado hasta la defensa pública.</p><br><br>
        @auth
            <a href="{{ url('/dashboard') }}" class="bg-white text-blue-700 px-6 py-3 rounded-lg font-semibold shadow hover:bg-gray-100 transition">Ir al Panel</a>
        @else
            <a href="{{ route('login') }}" class="bg-white text-blue-700 px-6 py-3 rounded-lg font-semibold shadow hover:bg-gray-100 transition">Iniciar sesión</a>
        @endauth
    </div>
</section>

    <!-- Características -->
    <section class="py-16 bg-gray-100">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-3 gap-10 text-center">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-xl font-semibold mb-2">Postulantes</h3>
                <p class="text-gray-600">Da seguimiento a tu proceso de titulación paso a paso.</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-xl font-semibold mb-2">Tutores & Tribunales</h3>
                <p class="text-gray-600">Dan seguimiento, evalúan y aprueban etapas rumbo a la defensa pública.</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-xl font-semibold mb-2">Documentación Oficial</h3>
                <p class="text-gray-600">Gestión centralizada de resoluciones, informes y memorándums.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white mt-10 py-4 text-center text-sm text-gray-500 relative z-10">
        <div class="max-w-7xl mx-auto px-6 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }}  SISMOD - Sistema Web de Seguimiento y Control de Modalidades de Graduación - Ingeniería Textil U.P.E.A.
            Todos los derechos reservados.<br>
            Diseño, producción, confección, Ingeniería Textil tu mejor elección<br>
            <small class="text-xs italic text-gray-400">Desarrolladora: Isabel Y. Cañaviri Robles</small>
        </div>
    </footer>
</body>

</html>
