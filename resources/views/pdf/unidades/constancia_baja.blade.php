<!DOCTYPE html>
<html lang="es">
@php
    $logo = "storage/uploads/logos/{$company->logo}";
    $img_firm = "storage/uploads/firms/{$company->img_firm}";
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
            height: 1.2cm;
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
            line-height: 1.5;
            position: relative;
            margin-top: 1.7cm;
            margin-bottom: 1.2cm;
            text-align: justify;
            orphans: 2;
            widows: 2;
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

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 15px;
        }

        .titulo {
            font-weight: bold;
            text-align: center;
            margin: 30px 0;
            font-size: 11px;
        }

        .text-center {
            text-align: center;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 0.5cm 0;
            text-transform: uppercase;
            position: relative;
        }

        .subtitle {
            font-size: 11px;
            font-weight: bold;
            margin: 0.5cm 0;
            text-transform: uppercase;
            text-align: center;
        }

        .content {
            font-size: 10px;
            line-height: 1.5;
            text-align: justify;
            margin: 0.5cm 0;
        }

        .fecha {
            text-align: left;
            margin: 0.2cm 0 0.5cm 0;
            font-size: 10px;
        }

        .firma {
            width: 40%;
            display: table-cell;
            vertical-align: top;
            text-align: left;
        }

        .firma img {
            max-width: 180px;
            max-height: 80px;
            margin: 0 auto;
            display: block;
        }

        .logo {
            text-align: center;
            margin: 0 auto;
            display: block;
        }

        .table-vehiculo {
            width: 100%;
            border-collapse: collapse;
            margin: 0.5cm 0;
        }

        .table-vehiculo td {
            padding: 0.15cm;
            font-size: 10px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .table-vehiculo td:first-child {
            width: 5.5cm;
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .baja-enfasis {
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }

        .destaque {
            font-weight: bold;
            margin: 0.5cm 0 0.3cm 0;
            font-size: 11px;
            text-align: left;
        }

        /* Sección inferior con firma y adjuntos */
        .bottom-section {
            width: 100%;
            margin-top: 0.2cm;
            display: table;
        }

        .adjuntos {
            width: 50%;
            display: table-cell;
            vertical-align: top;
            border: 1px dashed #aaa;
            padding: 0.1cm;
            border-radius: 4px;
        }

        .adjuntos ul {
            margin: 0;
            padding-left: 15px;
            columns: 2;
        }

        .adjuntos li {
            margin-bottom: 2px;
            font-size: 8px;
        }

        .seccion-titulo {
            font-weight: bold;
            font-size: 8px;
            margin-bottom: 0.1cm;
            background-color: #eaeaea;
            padding: 1px 2px;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>

<body>
    {{-- Marca de agua para estado ANULADA --}}
    @php
        $estado_constancia = isset($constancia->estado) ? strtoupper(trim($constancia->estado)) : '';
    @endphp
    @if ($estado_constancia === 'ANULADA')
        <div
            style="
            position: fixed;
            top: 35%;
            left: 0;
            width: 100vw;
            text-align: center;
            z-index: 9999;
            opacity: 0.18;
            font-size: 7em;
            color: #d32f2f;
            font-weight: bold;
            transform: rotate(-20deg);
            pointer-events: none;
            user-select: none;
        ">
            ANULADA
        </div>
    @endif
    <header>
        <div class="logo">
            @if ($company->logo)
                <img src="data:{{ mime_content_type(public_path("{$logo}")) }};base64, {{ base64_encode(file_get_contents(public_path("{$logo}"))) }}"
                    alt="{{ $company->name }}" class="company_logo" style="max-width: 90px; margin: 0 auto;">
            @else
                <img src="{{ asset('logo/tulogo.png') }}" alt="{{ $company->name }}" class="company_logo"
                    style="max-width: 90px; margin: 0 auto;">
            @endif

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
        <div class="title">CONSTANCIA DE BAJA</div>

        <div class="subtitle">
            EL GERENTE DE LA EMPRESA {{ strtoupper($company->name) }} QUE SUSCRIBE:
        </div>

        <div class="content">
            <p class="destaque">HACE CONSTAR:</p>
            @php
                // Determinar si estamos usando $constancia o $solicitud
                if (isset($constancia) && !is_null($constancia)) {
                    $unidad = $constancia->vehiculo;
                    $fecha_doc = $constancia->fecha_emision;
                } else {
                    $unidad = $solicitud->detalle->first()->infoVehiculo;
                    $fecha_doc = $solicitud->fecha;
                }
            @endphp
            <table class="table-vehiculo">
                <tr>
                    <td>Placa de Rodaje N°</td>
                    <td>
                        @if (isset($unidad['placa']))
                            {{ $unidad['placa'] }}
                        @else
                            {{ $unidad->placa }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Categoría</td>
                    <td>
                        @if (isset($unidad['categoria']))
                            {{ $unidad['categoria'] }}
                        @else
                            {{ $unidad->categoria }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Marca</td>
                    <td>
                        @if (isset($unidad['marca']['nombre']))
                            {{ $unidad['marca']['nombre'] }}
                        @else
                            {{ $unidad->marca->nombre }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Año</td>
                    <td>
                        @if (isset($unidad['year']))
                            {{ $unidad['year'] }}
                        @else
                            {{ $unidad->year }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Modelo</td>
                    <td>
                        @if (isset($unidad['modelo']['nombre']))
                            {{ $unidad['modelo']['nombre'] }}
                        @else
                            {{ $unidad->modelo->nombre }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>N° Interno</td>
                    <td>
                        @if (isset($unidad['numero_interno']))
                            {{ $unidad['numero_interno'] }}
                        @else
                            {{ $unidad->numero_interno }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Motor N°</td>
                    <td>
                        @if (isset($unidad['numero_motor']))
                            {{ $unidad['numero_motor'] }}
                        @else
                            {{ $unidad->numero_motor }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Color</td>
                    <td>
                        @if (isset($unidad['color']))
                            {{ $unidad['color'] }}
                        @else
                            {{ $unidad->color }}
                        @endif
                    </td>
                </tr>
            </table>
            <p>
                inscrita y registrada en Gerencia Transito y Transportes de la provincia de
                {{ $establishment->province->description }} en la empresa {{ $company->name }},
                <span class="baja-enfasis">HA SIDO DADO DE BAJA</span> de mi representado.
            </p>

            <p>
                Se expide la presente constancia a solicitud de la parte interesada para los fines
                convenientes.
            </p>
        </div>

        <div class="fecha" style="text-align: right; margin-bottom: 0.5cm;">
            {{ $establishment->district->description }},
            {{ \App\Helpers\DateHelper::formatoEspanol($fecha_doc) }}.
        </div>

        <div class="bottom-section">
            <div class="adjuntos">
                <div class="seccion-titulo">DOCUMENTOS ADJUNTOS</div>
                <ul>
                    <li>Solicitud de Baja</li>
                    <li>Copia de Tarjeta de propiedad</li>
                </ul>
            </div>
            <div style="width: 10%; display: table-cell;"></div> <!-- Separación lateral -->
            <div class="firma">
                @if ($company->img_firm)
                    <img src="data:{{ mime_content_type(public_path("{$img_firm}")) }};base64, {{ base64_encode(file_get_contents(public_path("{$img_firm}"))) }}"
                        style="max-width: 180px; margin: 10px auto;">
                @else
                    <img src="https://placehold.co/150x50" alt="IMAGEN DE FIRMA"
                        style="max-width: 180px; margin: 10px auto;">
                @endif
            </div>
        </div>

        <!-- Reservar espacio para el footer -->
        <div class="footer-space"></div>
    </main>
</body>

</html>
