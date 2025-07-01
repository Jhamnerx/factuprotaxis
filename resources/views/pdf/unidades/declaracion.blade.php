<!DOCTYPE html>
<html lang="es">
@php
    $logo = "storage/uploads/logos/{$company->logo}";
    $img_firm = "storage/uploads/firms/{$company->img_firm}";
    if (isset($vehiculo)) {
        $unidad = $vehiculo;
    } elseif (isset($solicitud)) {
        $unidad = $solicitud->detalle->first()->infoVehiculo;
    } elseif (isset($declaracion) && isset($declaracion->datosVehiculo)) {
        $unidad = $declaracion->datosVehiculo;
    }
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Declaración Jurada</title>
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
            font-size: 12px;
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

        .logo {
            text-align: center;
            margin: 0 auto;
            display: block;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 0.5cm 0;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .paragraph {
            margin: 15px 0;
            text-align: justify;
            line-height: 1.5;
            font-size: 12px;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .bold {
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0 15px 0;
            text-transform: uppercase;
            font-size: 13px;
        }

        .vehicle-info {
            margin: 15px 0;
            line-height: 1.5;
            padding: 5px 0;
            font-size: 12px;
        }

        /* Tabla de datos del vehículo */
        .table-vehiculo {
            width: 100%;
            border-collapse: collapse;
            margin: 0.5cm 0;
        }

        .table-vehiculo td {
            padding: 0.15cm;
            font-size: 12px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .table-vehiculo td:first-child {
            width: 5.5cm;
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .footer-date {
            margin-top: 20px;
            text-align: right;
            font-size: 12px;
        }

        .signature-section {
            margin-top: 40px;
            text-align: center;
        }

        .company-signature {
            font-weight: bold;
            margin-bottom: 5px;
            text-align: center;
            text-transform: uppercase;
            font-size: 12px;
        }

        .img-signature {
            max-width: 150px;
            max-height: 70px;
            margin: 0 auto;
            display: block;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 5px;
            text-transform: uppercase;
            font-size: 12px;
        }

        .signature-title {
            font-size: 9px;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
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
        <div class="title">DECLARACIÓN JURADA</div>

        <div class="paragraph uppercase">
            CONSTE POR EL PRESENTE DOCUMENTO LA EMPRESA <span class="bold">{{ $company->name }}</span>, REPRESENTADO
            POR SU GERENTE GENERAL <span class="bold">{{ $establishment->representative_name }}</span>, IDENTIFICADO
            CON DNI
            N° {{ $establishment->district_id }}, CON DIRECCIÓN {{ $establishment->address }} Y EN
            SUS FACULTADES EXPUESTAS EN LA VIGENCIA DE PODER; A LA FECHA EN USO
            DE MIS FACULTADES Y DERECHOS.
        </div>

        <div class="section-title">DECLARO BAJO JURAMENTO DE LEY:</div>

        <table class="table-vehiculo">
            <tr>
                <td>Placa de Rodaje N°</td>
                <td>
                    @if (isset($unidad->placa))
                        {{ $unidad->placa }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Marca</td>
                <td>
                    @if (isset($unidad->marca->nombre))
                        {{ $unidad->marca->nombre }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Modelo</td>
                <td>
                    @if (isset($unidad->modelo->nombre))
                        {{ $unidad->modelo->nombre }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Color</td>
                <td>
                    @if (isset($unidad->color))
                        {{ $unidad->color }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Año</td>
                <td>
                    @if (isset($unidad->year))
                        {{ $unidad->year }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>N° de Motor</td>
                <td>
                    @if (isset($unidad->numero_motor))
                        {{ $unidad->numero_motor }}
                    @endif
                </td>
            </tr>
        </table>

        <div class="paragraph uppercase bold">
            ASIMISMO, ME COMPROMETO A CUMPLIR LAS DISPOSICIONES DE LA
            MUNICIPALIDAD DE {{ $establishment->province->description }} – TRÁNSITO Y TRANSPORTES A FIN DE
            AUTORIZAR EL REGISTRO DE LA UNIDAD VEHICULAR ANTES MENCIONADA
            EL CUAL DISPONE PASAR LA CONSTATACIÓN DE CARACTERÍSTICAS
            PERIÓDICA.
        </div>

        <div class="paragraph uppercase">
            DECLARACIÓN JURADA QUE REALIZO AL AMPARO DEL PRINCIPIO DE PRESUNCIÓN
            DE VERACIDAD ESTABLECIDO EN EL NUMERAL 1.7 DEL ART. IV DE LA LEY Nº 27444 –
            LEY DE PROCEDIMIENTO ADMINISTRATIVO GENERAL; EN CONSECUENCIA, NOS
            RESPONSABILIZAMOS POR LAS ACCIONES CIVILES, PENALES Y ADMINISTRATIVAS
            QUE PUEDA DAR LUGAR LA PRESENTE Y PARA MAYOR CONSTANCIA.
        </div>

        <div class="footer-date uppercase">
            {{ $establishment->district->description }}, {{ \App\Helpers\DateHelper::formatoEspanol(now()) }}.
        </div>
        <div class="signature-section uppercase">
            <div style="text-align: center; margin: 40px auto 15px auto;">
                <div class="company-signature">{{ $company->name }}</div>
                <div class="company-signature" style="margin-bottom: 30px;">{{ $company->trade_name }}</div>

                <div style="margin-bottom: 5px;">
                    <div style="width: 7cm; border-top: 1px solid #000; margin: 0 auto;"></div>
                </div>

                <div class="signature-name">{{ $company->representante_legal_name }}</div>
                <div class="signature-title">Gerente General</div>
            </div>
        </div>

        <!-- Reservar espacio para el footer -->
        <div class="footer-space"></div>
    </main>
</body>

</html>
