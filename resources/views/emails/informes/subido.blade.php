<x-mail::message>
# 📄 Informe de Revisión Subido

Estimado/a **{{ $postulante->name ?? 'Postulante' }}**,

El tribunal líder ha subido el **informe de revisión** correspondiente a tu avance **"{{ $avance->titulo }}"**.

- 📅 **Fecha de entrega del avance:** {{ \Carbon\Carbon::parse($avance->fecha_entrega)->format('d/m/Y') }}
- ⏳ **Fecha límite de revisión:** {{ \Carbon\Carbon::parse($avance->fecha_limite_revision)->format('d/m/Y') }}
- 📌 **Estado actual:** {{ ucfirst($avance->estado) }}

@if ($tutor)
Este informe también fue notificado al tutor **{{ $tutor->name }}**.
@endif

<x-mail::button :url="route('avances.mis')">
Ver mis avances
</x-mail::button>

Gracias,<br>
**{{ config('app.name') }}**
</x-mail::message>

