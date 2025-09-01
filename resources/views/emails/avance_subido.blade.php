<x-mail::message>
# 📢 Nuevo avance recibido

Estimado Tribunal de grado,

Se le notifica que el postulante **{{ $postulante->nombre }} {{ $postulante->paterno }}** ha subido un nuevo avance de su trabajo de grado.

- 📅 **Fecha de entrega:** {{ \Carbon\Carbon::parse($avance->fecha_entrega)->format('d/m/Y') }}
- ⏳ **Fecha límite de revisión:** {{ \Carbon\Carbon::parse($avance->fecha_limite_revision)->format('d/m/Y') }}


El documento se encuentra adjunto a este correo.

<x-mail::button :url="url('/')">
Ir al Sistema
</x-mail::button>

Gracias,<br>
**{{ config('app.name') }}**
</x-mail::message>
