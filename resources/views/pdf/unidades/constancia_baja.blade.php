<!DOCTYPE html>
<html lang="es">
@php
    $logo = "storage/uploads/logos/{$company->logo}";
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia de Baja</title>
    <style>
        {!! $stylesheet !!} @page {
            margin-top: 0.5cm;
            margin-bottom: 1.5cm;
            margin-left: 2cm;
            margin-right: 2cm;
        }

        * {
            orphans: 2;
            widows: 2;
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: a.2cm;
            text-align: center;
            padding-top: 3px;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 1cm;
            text-align: center;
            font-size: 8px;
            padding-top: 5px;
            border-top: 1px solid #ccc;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            position: relative;
            margin-top: 1.7cm;
            margin-bottom: 1.2cm;
            text-align: justify;
        }

        /* Contenido principal centrado */
        main {
            width: 100%;
            max-width: 17cm;
            margin: 0 auto;
            padding: 0 0.3cm;
        }

        /* Espacio para el header */
        .header-space {
            height: 0.1cm;
            display: block;
            margin-bottom: 0.2cm;
        }

        /* Espacio para el footer */
        .footer-space {
            height: 1cm;
            display: block;
        }

        .text-center {
            text-align: center;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .content {
            font-size: 14px;
            line-height: 1.5;
            text-align: justify;
        }

        .fecha {
            margin-top: 30px;
            text-align: right;
        }

        .firma {
            margin-top: 80px;
            text-align: center;
        }

        .linea-firma {
            border-top: 1px solid #000;
            width: 250px;
            margin: 0 auto;
        }

        .nombre-firma {
            margin-top: 5px;
            font-weight: bold;
        }

        .header {
            margin-bottom: 30px;
        }

        .logo {
            max-width: 150px;
            max-height: 100px;
        }

        table.detalles {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.detalles th,
        table.detalles td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table.detalles th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <header>
        <div class="center-logo">
            <img src="data:{{ mime_content_type(public_path("{$logo}")) }};base64, {{ base64_encode(file_get_contents(public_path("{$logo}"))) }}"
                alt="{{ $company->name }}" class="company_logo" style="max-width: 90px; margin: 0 auto;">
        </div>
    </header>

    <footer>
        <div>
            RUC: {{ $company->number }} | {{ $establishment->address }} | {{ $establishment->email }}
            @if ($establishment->telephone)
                | Tel: {{ $establishment->telephone }}
            @endif
        </div>
    </footer>

    <!-- Este div mantiene el espacio adecuado entre el header y el contenido en todas las páginas -->
    <div class="header-space"></div>

    <main>
        <div class="header">
            <table width="100%">
                <tr>
                    <td width="100%" class="text-center">
                        <h1>{{ $company->name }}</h1>
                        <p>{{ $establishment->address }} - {{ $establishment->department }},
                            {{ $establishment->province }},
                            {{ $establishment->district }}</p>
                    </td>
                    <td width="20%">
                        <div class="text-center">
                            <span>Fecha de emisión:</span>
                            <br>
                            <span><strong>{{ \App\Helpers\DateHelper::formatoEspanol(now(), true, false) }}</strong></span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="title">Constancia de Baja</div>

        <div class="content">
            <p>
                Por medio de la presente, <strong>{{ $company->name }}</strong>, con RUC
                <strong>{{ $company->number }}</strong>,
                HACE CONSTAR que la(s) siguiente(s) unidad(es) vehicular(es) ha(n) sido dada(s) de baja en nuestro
                sistema
                de registro
                según la solicitud N° <strong>{{ $solicitud->id }}</strong> con fecha
                <strong>{{ \App\Helpers\DateHelper::formatoEspanol($solicitud->fecha, true, false) }}</strong>.
            </p>

            <table class="detalles">
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Propietario</th>
                        <th>Motivo de Baja</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($solicitud->detalle as $detalle)
                        <tr>
                            <td>{{ $detalle->vehiculo ? $detalle->vehiculo['placa'] : 'N/A' }}</td>
                            <td>{{ $detalle->propietario ? $detalle->propietario['name'] : 'N/A' }}</td>
                            <td>{{ $solicitud->motivo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p>
                Esta constancia certifica que la(s) unidad(es) vehicular(es) mencionada(s) ya no forma(n) parte de
                nuestra
                flota
                de taxis registrados y ha(n) sido dada(s) de baja en nuestro sistema para todos los efectos
                administrativos
                y legales.
            </p>

            <p>
                La presente constancia se expide a solicitud de la parte interesada para los fines que estime
                conveniente.
            </p>
        </div>
        <div class="fecha">
            {{ $establishment->district->description }},
            {{ \App\Helpers\DateHelper::formatoEspanol($solicitud->fecha) }}
        </div>

        <div class="firma">
            <div class="linea-firma"></div>
            <div class="nombre-firma">{{ $company->name }}</div>
            <div>{{ $company->trade_name }}</div>
            <div>RUC: {{ $company->number }}</div>
        </div>

        <!-- Reservar espacio para el footer -->
        <div class="footer-space"></div>
        </div>
    </main>
</body>

</html>
