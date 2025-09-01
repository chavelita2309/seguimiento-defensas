<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe Final</title>
    <style>
        @page {
        margin: 2cm;
    }
        body { font-family: 'Times New Roman', Times, serif, sans-serif; font-size: 12pt;  line-height: 1.8;  margin: 0; }
        h1, h2, h3 { text-align: center; }
        .firmas { margin-top: 60px; text-align: center; }
        .firma { display: inline-block; width: 30%; text-align: center; margin: 0 1%; }
        .header-info { text-align: left; margin-bottom: 20px; }
        .header-info p { margin: 0; line-height: 1.5; }

        .firma-nombre {
    font-size: 9pt; 
}
    </style>
</head>
<body>

    <h2>INFORME FINAL DE CONFORMIDAD DE TRABAJO DE GRADO</h2>

    <div class="header-info">
        <p><b>A:</b> Director de Carrera de Ingeniería Textil</p>
         <p><b>Fecha:</b> {{ \Carbon\Carbon::now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</p>
        
    </div>

    <p style="text-align: justify;">
        De nuestra mayor consideración:<br><br>
        Por intermedio de la presente, el tribunal revisor de grado hace constar su conformidad con la finalización del trabajo titulado:
        <b>{{ $proyecto->titulo }}</b>, presentado por el(la) postulante
        <b>{{ $proyecto->postulante->nombre }} {{ $proyecto->postulante->paterno }} {{ $proyecto->postulante->materno }}</b>, con CI. <b>{{ $proyecto->postulante->ci }}</b>,
        y R.U. <b>{{ $proyecto->postulante->ru }}</b>, bajo la modalidad de <b>{{ $proyecto->modalidad->nombre }}</b>, toda vez que ha sido revisado y habiendo sido subsanadas las observaciones realizadas, 
        se solicita planificar la defensa pública de la investigación presentada.<br><br>
        Con este informe, se da por concluido el proceso de revisión del trabajo de grado, quedando a la espera de la programación de la defensa pública.<br><br>
        Atentamente,
    </p>

    <div class="firmas">
    @foreach ($proyecto->tribunales as $tribunal)
        <div class="firma">
            _______________________<br>
            <span class="firma-nombre">{{ $tribunal->grado }} {{ $tribunal->nombre }} {{ $tribunal->paterno }} {{ $tribunal->materno }}</span><br>
            Tribunal revisor de grado
        </div>
    @endforeach
</div>

</body>
</html>
