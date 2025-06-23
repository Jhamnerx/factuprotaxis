<!DOCTYPE html>
<html lang="es">
@php
    $logo = "storage/uploads/logos/{$company->logo}";
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Baja de Taxi Independiente</title>
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

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .titulo-solicito {
            font-weight: bold;
            margin: 30px 0 20px 0;
            text-align: right;
        }

        .solicito-contenido {
            margin-left: 20px;
            font-weight: bold;
        }

        .titulo {
            font-weight: bold;
            text-align: center;
            margin: 30px 0;
            font-size: 11px;
        }

        .datos-solicitante {
            text-align: justify;
            margin: 20px 0;
            line-height: 1.6;
        }

        .nombre-solicitante {
            font-weight: bold;
            text-transform: uppercase;
        }

        .dni-texto {
            font-weight: normal;
        }

        .texto {
            text-align: justify;
            margin: 20px 0;
        }

        .texto p {
            margin-bottom: 0.3cm;
            text-align: justify;
            page-break-inside: auto;
        }

        .texto-baja {
            font-weight: bold;
            text-transform: uppercase;
        }

        .subtitulo {
            font-weight: bold;
            margin: 25px 0 15px 0;
            text-align: center;
            font-size: 11px;
        }

        .cierre {
            text-align: justify;
            margin: 20px 0;
        }

        .cierre p {
            margin-bottom: 0.3cm;
            text-align: justify;
        }

        .fecha {
            text-align: right;
            margin: 40px 0 20px 0;
            font-style: normal;
        }

        .adjuntos {
            margin-top: 25px;
            font-size: 10px;
            border-top: 1px dotted #aaa;
            padding-top: 10px;
        }

        .adjuntos ul {
            margin: 5px 0 0 0;
            padding-left: 25px;
        }

        .adjuntos-titulo {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .firma {
            text-align: center;
            margin-top: 80px;
            margin-bottom: 30px;
        }

        .firma-linea {
            border-top: 1px solid #000;
            width: 250px;
            margin: 0 auto;
        }

        .firma-nombre {
            text-align: center;
            margin-top: 8px;
            font-weight: bold;
        }

        .firma-dni {
            text-align: center;
            font-size: 11px;
        }

        .firma-empresa {
            text-align: center;
            margin-top: 80px;
            margin-bottom: 20px;
        }

        .firma-empresa img {
            max-width: 150px;
            display: block;
            margin: 0 auto;
        }

        .firma-empresa-nombre {
            text-align: center;
            font-weight: bold;
            margin-top: 5px;
        }

        .firma-empresa-cargo {
            text-align: center;
            font-size: 10px;
            text-transform: uppercase;
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
        <div class="titulo-solicito">
            <table>
                <tr>
                    <td width="80" valign="top"><strong>SOLICITO:</strong></td>
                    <td class="solicito-contenido">
                        Baja de vehículo de <strong>TAXI INDEPENDIENTE</strong>
                    </td>
                </tr>
            </table>
        </div>

        <div class="titulo">
            SEÑOR ALCALDE DE LA MUNICIPALIDAD PROVINCIAL DE HUANCAYO.
        </div>
        <div class="datos-solicitante">
            <span class="nombre-solicitante">Gregorio MALLQUI MEZA</span>, identificado con DNI N°19804301, CELULAR N°
            <strong>984760460</strong> con domicilio en el Jr. Manco Capac s/n Psj Sanchez Cerro 160, distrito de
            Pilcomayo Huancayo, provincia de Huancayo Huancayo, ante usted, con debido respeto, me presento y expongo:
        </div>

        <div class="texto">
            <p>Que, habiendo realizado la COMPRA del vehículo de placa <strong>W2B-255</strong> y encontrándose
                registrado
                en taxis independiente, por lo que solicito <span class="texto-baja">BAJA CON LA FINALIDAD DE REGISTRAR
                    EN
                    OTRA EMPRESA</span></p>
        </div>

        <div class="subtitulo">
            POR LO EXPUESTO:
        </div>

        <div class="cierre">
            <p>Ruego a usted Sr. alcalde, acceder a mi petición, por considerarlo de justicia que espero alcanzar.</p>
        </div>
        <div class="fecha">
            Huancayo, {{ \App\Helpers\DateHelper::formatoEspanol(now()) }}
        </div>

        <div class="adjuntos">
            <div class="adjuntos-titulo">DOCUMENTOS ADJUNTOS:</div>
            <ul>
                <li>Copia de DNI</li>
                <li>Copia de Tarjeta de propiedad</li>
            </ul>
        </div>

        <div class="firma">
            <div class="firma-linea"></div>
            <div class="firma-nombre">Gregorio MALLQUI MEZA</div>
            <div class="firma-dni">DNI N°19804301</div>
        </div>

        <div class="firma-empresa">
            <img src="{{ public_path('images/firma_gerente.png') }}" alt="Firma Gerente">
            <div class="firma-empresa-nombre">Eder Pedro Hidalgo Hilario</div>
            <div class="firma-empresa-cargo">GERENTE GENERAL</div>
        </div><!-- Reservar espacio para el footer -->
        <div class="footer-space"></div>
    </main>
</body>

</html>
