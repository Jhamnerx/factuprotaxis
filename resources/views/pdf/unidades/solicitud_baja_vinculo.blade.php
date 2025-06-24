<!DOCTYPE html>
<html lang="es">
@php
    $logo = "storage/uploads/logos/{$company->logo}";
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Baja por Pérdida de Vínculo Contractual</title>
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
            margin: 50px 0 20px 0;
        }

        .solicito-contenido {
            margin-left: 120px;
            margin-right: 120px;
        }

        .titulo {
            font-weight: bold;
            text-align: center;
            margin: 30px 0;
        }

        .datos-solicitante {
            text-align: justify;
            margin: 20px 0;
        }

        .nombre-solicitante {
            font-weight: bold;
        }

        .dni-texto {
            font-weight: normal;
        }

        .texto {
            text-align: justify;
            margin: 20px 0;
        }

        .texto-baja {
            font-weight: bold;
            text-transform: uppercase;
        }

        .subtitulo {
            font-weight: bold;
            margin: 20px 0 10px 0;
            text-align: center;
        }

        .cierre {
            text-align: justify;
            margin: 20px 0;
        }

        .fecha {
            text-align: right;
            margin: 30px 0;
        }

        .adjuntos {
            margin-top: 15px;
            font-size: 10px;
        }

        .adjuntos ul {
            margin: 0;
            padding-left: 20px;
        }

        .firma {
            text-align: center;
            margin-top: 60px;
            margin-bottom: 20px;
        }

        .firma-linea {
            border-top: 1px solid #000;
            width: 250px;
            margin: 0 auto;
        }

        .firma-nombre {
            text-align: center;
            margin-top: 5px;
        }

        .firma-dni {
            text-align: center;
            font-size: 11px;
        }

        .firma-empresa {
            text-align: center;
            margin-top: 60px;
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
            <table width="100%">
                <tr>
                    <td width="100" valign="top"><strong>SOLICITO:</strong></td>
                    <td class="solicito-contenido">
                        Baja de vehículo POR PERDIDA DE VINCULO CONTRACTUAL POR DECLARACION JURADA.
                    </td>
                </tr>
            </table>
        </div>

        <div class="titulo">
            SEÑOR ALCALDE DE LA MUNICIPALIDAD PROVINCIAL DE HUANCAYO.
        </div>

        <div class="datos-solicitante">
            <span class="nombre-solicitante">Katerin Vanesa PORRAS SOSA</span>, identificado con DNI N°70140841, CELULAR
            N° <strong>984760460</strong> con domicilio en el Jr. Las Magnolias N°523, distrito de El Tambo, provincia
            de Huancayo, ante usted, con debido respeto, me presento y expongo:
        </div>

        <div class="texto">
            Que, habiendo realizado la compra del vehículo de placa <strong>W4B-190</strong> y encontrándose registrado
            en empresa de taxis, por lo que solicito <span class="texto-baja">BAJA POR PERDIDA DE VINCULO
                CONTRACTUAL</span>, a fin de realizar los trámites y registrar mi unidad en otra empresa.
        </div>

        <div class="subtitulo">
            POR LO EXPUESTO:
        </div>

        <div class="cierre">
            Ruego a usted Sr. alcalde, acceder a mi petición, por considerarlo de justicia que espero alcanzar.
        </div>
        <div class="fecha">
            {{ $establishment->district->description }},
            {{ \App\Helpers\DateHelper::formatoEspanol($solicitud->fecha) }}.
        </div>

        <div class="adjuntos">
            DOCUMENTOS ADJUNTO
            <ul>
                <li>Copia de tarjeta de propiedad</li>
                <li>Copia de DNI</li>
            </ul>
        </div>

        <div class="firma">
            <div class="firma-linea"></div>
            <div class="firma-nombre">Katerin Vanesa PORRAS SOSA</div>
            <div class="firma-dni">DNI N°70140841</div>
        </div>

        <div class="firma-empresa">
            <img src="{{ public_path('images/firma_gerente.png') }}" alt="Firma Gerente">
            <div class="firma-empresa-nombre">Eder Pedro Hidalgo Hilario</div>
            <div class="firma-empresa-cargo">GERENTE GENERAL</div>
        </div> <!-- Reservar espacio para el footer -->
        <div class="footer-space"></div>
    </main>
</body>

</html>
