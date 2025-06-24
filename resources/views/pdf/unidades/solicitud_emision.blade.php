<!DOCTYPE html>
<html lang="es">
@php
    $logo = "storage/uploads/logos/{$company->logo}";
    $img_firm = "storage/uploads/firms/{$company->img_firm}";
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Emisión de Tarjeta Única de Circulación</title>
    <style>
        @page {
            margin-top: 0.5cm;
            margin-bottom: 1.5cm;
            margin-left: 2cm;
            margin-right: 2cm;
            size: 21cm 29.7cm;
            /* A4 */
        }

        * {
            box-sizing: border-box;
            orphans: 3;
            widows: 3;
        }

        /* Control de saltos de página */
        tr,
        td,
        th,
        table,
        thead,
        tbody {
            page-break-inside: auto;
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 1.8cm;
            text-align: center;
            padding-top: 8px;
            background-color: rgba(255, 255, 255, 0.98);
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 1.2cm;
            text-align: center;
            font-size: 8px;
            padding-top: 8px;
            border-top: 1px solid #d0d0d0;
            background-color: rgba(255, 255, 255, 0.98);
            color: #444;
        }

        body {
            font-family: 'DejaVu Serif', serif;
            font-size: 11px;
            line-height: 1.6;
            position: relative;
            margin-top: 2.2cm;
            margin-bottom: 1.5cm;
            text-align: justify;
            color: #333;
            background-color: white;
        }

        main {
            width: 100%;
            max-width: 17cm;
            margin: 0 auto;
            padding: 0 0.2cm;
        }

        .header-space {
            height: 0.3cm;
            display: block;
            margin-bottom: 0.5cm;
        }

        .footer-space {
            height: 1.2cm;
            display: block;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 10px;
            background-color: white;
        }

        .sumilla {
            text-align: right;
            margin: 25px 0 20px 0;
            font-weight: bold;
            font-size: 12px;
            color: #222;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            padding-bottom: 5px;
        }

        .atencion {
            text-align: right;
            margin: 15px 0;
            font-weight: bold;
            font-size: 10.5px;
            color: #333;
        }

        .titulo {
            text-align: center;
            font-weight: bold;
            margin: 25px 0;
            font-size: 12px;
            letter-spacing: 0.4px;
            color: #000;
            text-transform: uppercase;
        }

        .cuerpo {
            text-align: justify;
            margin: 20px 0;
            line-height: 1.7;
            color: #333;
        }

        .nombre-empresa {
            font-weight: bold;
            color: #000;
        }

        .email {
            color: #0055aa;
            text-decoration: underline;
        }

        .tabla-unidades {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            page-break-inside: auto !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            border: 1px solid #999;
        }

        .tabla-unidades thead {
            display: table-header-group;
            background-color: #f0f0f0;
        }

        .tabla-unidades th,
        .tabla-unidades td {
            border: 1px solid #999;
            padding: 7px 9px;
            text-align: center;
            font-size: 10px;
        }

        .tabla-unidades th {
            background-color: #e0e0e0;
            background: linear-gradient(to bottom, #f0f0f0, #e0e0e0);
            color: #111;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 2px solid #777;
            padding: 8px 9px;
        }

        .tabla-unidades tr {
            page-break-inside: auto !important;
        }

        .tabla-unidades tbody tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        .tabla-unidades tbody td strong {
            color: #000;
            font-weight: 600;
        }

        #tabla-unidades-container {
            page-break-inside: auto !important;
            break-inside: auto;
        }

        .conclusion {
            font-weight: bold;
            font-size: 11px;
            text-align: left;
            text-decoration: underline;
            margin: 35px 0 20px 0;
            color: #222;
            letter-spacing: 0.3px;
        }

        .fecha {
            text-align: right;
            margin: 1cm 0 0.7cm 0;
            font-size: 10px;
            color: #444;
            font-style: italic;
        }

        .firma-container {
            width: 100%;
            text-align: center;
            margin-top: 1cm;
            position: relative;
        }

        .logo {
            text-align: center;
            margin: 0 auto;
            display: block;
        }

        .firma-img {
            max-width: 150px;
            max-height: 70px;
            margin: 0 auto 10px auto;
            display: block;
        }

        .company_logo {
            max-height: 80px;
            max-width: 120px;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">
            <img src="data:{{ mime_content_type(public_path("{$logo}")) }};base64, {{ base64_encode(file_get_contents(public_path("{$logo}"))) }}"
                alt="{{ $company->name }}" class="company_logo" style="max-width: 90px; margin: 0 auto;">
        </div>
    </header>

    <footer>
        <div>
            <strong>RUC: {{ $company->number }}</strong> | {{ $establishment->address }} |
            <span style="color:#0055aa;">{{ $establishment->email }}</span>
            @if ($establishment->telephone)
                | Tel: {{ $establishment->telephone }}
            @endif
        </div>
    </footer>

    <div class="header-space"></div>

    <main>
        <div class="container">
            <div class="sumilla">
                SOLICITO EMISIÓN DE TARJETA ÚNICA DE CIRCULACIÓN
            </div>

            <div class="atencion">
                ATENCIÓN: GERENCIA DE TRANSITO Y TRANSPORTES
            </div>

            <div class="titulo">
                SEÑOR ALCALDE DE LA MUNICIPALIDAD PROVINCIAL DE HUANCAYO
            </div>
            <div class="cuerpo">
                <p>
                    La Empresa <span class="nombre-empresa">{{ $company->name }}</span>,
                    con RUC N°
                    {{ $company->number }}, domicilio real en {{ $establishment->address }}, Distrito de
                    {{ $establishment->district->description }}, Provincia
                    de {{ $establishment->province->description }}, Departamento de
                    {{ $establishment->department->description }}, correo electrónico <span
                        class="email">{{ $establishment->email }}</span>,
                    con teléfono N°
                    {{ $establishment->telephone }}, representado por el Gerente General <span
                        class="nombre-empresa">{{ $solicitud->representante_legal_name }}</span>,
                    identificado con DNI N° {{ $solicitud->representante_legal_dni }}, teléfono celular N°
                    {{ $solicitud->representante_legal_phone }}, Partida Electrónica N°
                    {{ $solicitud->partida_registral }}, con vigencia de poder y contando con las
                    facultades previstas para lo solicitado, ante Ud. en atenta forma digo:
                </p>
                <p>
                    Que, habiendo cumplido con los requisitos requeridos por su representada para la emisión de Tarjeta
                    Única de Circulación, solicito se imprima y se haga entrega del TUC, a fin de evitar perjuicio a los
                    conductores de <span class="nombre-empresa">{{ $company->name }}</span> de las siguientes
                    Unidades:
                </p>
            </div>
            <div id="tabla-unidades-container">
                <table class="tabla-unidades">
                    <thead>
                        <tr>
                            <th style="width: 10%;">N°</th>
                            <th style="width: 25%;">FLOTA</th>
                            <th style="width: 35%;">PLACA</th>
                            <th style="width: 30%;">CC N°</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($solicitud->detalle ?? [] as $index => $unidad)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>{{ $unidad->infoVehiculo->flota }}</td>
                                <td><strong>{{ $unidad->infoVehiculo->placa }}</strong></td>
                                <td>{{ $unidad->infoVehiculo->ccn }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="conclusion">
                POR LO EXPUESTO:
            </div>

            <div class="cuerpo">
                <p>
                    Ruego a usted Sr. alcalde, acceder a mi petición, por considerarlo de justicia que espero alcanzar.
                </p>
            </div>

            <div class="fecha">
                {{ $establishment->district->description }}, {{ \App\Helpers\DateHelper::formatoEspanol(now()) }}
            </div>

            <div class="firma-container">
                @if (file_exists(public_path("{$img_firm}")))
                    <img class="firma-img"
                        src="data:{{ mime_content_type(public_path("{$img_firm}")) }};base64, {{ base64_encode(file_get_contents(public_path("{$img_firm}"))) }}"
                        alt="Firma">
                @endif
            </div>
            <div class="footer-space"></div>
        </div>
    </main>
</body>

</html>
