<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Sistema Web de Seguimiento y Control de Modalidades de Graduación- Ingeniería Textil U.P.E.A.') }}
        </h2>
    </x-slot>
     
    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Hola, {{ auth()->user()->name }}!</h3>
            <p class="text-gray-700 leading-relaxed">
                Este sistema te permite realizar el seguimiento y control de las modalidades de graduación 
                en la carrera de Ingeniería Textil de la U.P.E.A. Podrás gestionar el avance 
                de los proyectos de grado o tesis de grado tanto si eres postulante, tutor o tribunal.
            </p>
        </div>
    </div>
</x-app-layout>
