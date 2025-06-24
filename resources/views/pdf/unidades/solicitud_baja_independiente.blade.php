<!DOCTYPE html>
<html lang="es">
@php
    $logo = "storage/uploads/logos/{$company->logo}";
    $img_firm = "storage/uploads/firms/{$company->img_firm}";
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
            text-align: left;
            margin: 0.2cm 0 0.5cm 0;
            /* Añado margen inferior de 0.5cm */
            font-size: 10px;
            /* Ajustado al tamaño base del documento */
        }

        /* Sección inferior con firma y adjuntos */
        .bottom-section {
            width: 100%;
            margin-top: 0.2cm;
            display: table;
        }

        .firma {
            width: 50%;
            display: table-cell;
            vertical-align: top;
            text-align: center;
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

        .firma {
            width: 40%;
            display: table-cell;
            vertical-align: top;
            text-align: left;
        }

        .firma-linea {
            border-top: 1px dashed #000;
            width: 180px;
            margin: 0 auto 15px auto;
        }

        .firma-nombre {
            text-align: center;
            margin-top: 5px;
            font-weight: bold;
            font-size: 10px;
        }

        .firma-dni {
            text-align: center;
            font-size: 10px;
            margin-top: 3px;
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
            <span class="nombre-solicitante">{{ $propietario->name }}</span>, identificado con DNI
            N°{{ $propietario->number }}, CELULAR N°
            <strong>{{ $propietario->telephone }}</strong> con domicilio en {{ $propietario->address }}, distrito
            de
            {{ $propietario->district->description }}, provincia de {{ $propietario->province->description }}, ante
            usted, con debido respeto, me presento y expongo:
        </div>

        <div class="texto">
            <p>Que, habiendo realizado la COMPRA del vehículo de placa <strong>{{ $vehiculo->placa }}</strong> y
                encontrándose
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
        <div class="fecha" style="text-align: right; margin-bottom: 1cm;">
            {{ $establishment->district->description }},
            {{ \App\Helpers\DateHelper::formatoEspanol(now()) }}
        </div>

        <div class="bottom-section">
            <div class="adjuntos">
                <div class="seccion-titulo">DOCUMENTOS ADJUNTOS</div>
                <ul>
                    <li>Copia de DNI</li>
                    <li>Copia de Tarjeta de propiedad</li>
                </ul>
            </div>
            <div class="firma">
                <div class="firma-linea"></div>
                <div class="firma-nombre">{{ $propietario->name }}</div>
                <div class="firma-dni">DNI N°{{ $propietario->number }}</div>
            </div>
        </div><!-- Reservar espacio para el footer -->
        <div class="footer-space"></div>
    </main>
</body>

</html>
