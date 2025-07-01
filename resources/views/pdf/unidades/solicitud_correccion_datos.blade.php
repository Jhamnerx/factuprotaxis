<!DOCTYPE html>
<html lang="es">
@php
    $logo = "storage/uploads/logos/{$company->logo}";
    $img_firm = "storage/uploads/firms/{$company->img_firm}";

@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Corrección de Datos</title>
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
            margin: 0 auto;
        }

        .sumilla {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
        }

        .atencion {
            text-align: center;
            margin: 10px 0;
            font-weight: bold;
        }

        .titulo {
            text-align: center;
            font-weight: bold;
            margin: 20px 0;
        }

        .cuerpo {
            text-align: justify;
            margin: 20px 0;
        }

        .nombre-empresa {
            font-weight: bold;
        }

        .email {
            color: blue;
        }

        .tabla-correccion {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        .tabla-correccion th,
        .tabla-correccion td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .conclusiones {
            margin-top: 20px;
        }

        .conclusiones p {
            margin-bottom: 10px;
        }

        .adjunto {
            margin-top: 20px;
        }

        .firma {
            text-align: right;
            margin-top: 50px;
        }

        .firma img {
            max-width: 150px;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }

        .rainbow-line {
            height: 2px;
            background: linear-gradient(to right, #ff9999, #ffcc99, #ffff99, #99ff99, #99ffff, #9999ff, #ff99ff);
            margin-top: 50px;
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
        <div class="container">
            <div class="sumilla">
                SUMILLA: SOLICITO CORRECCIÓN DE DATOS DE REGISTRO DE UNIDAD MÓVIL
            </div>

            <div class="atencion">
                ATENCIÓN: GERENCIA DE TRANSITO Y TRANSPORTES.
            </div>

            <div class="titulo">
                SEÑOR ALCALDE DE LA MUNICIPALIDAD PROVINCIAL DE HUANCAYO.
            </div>

            <div class="cuerpo">
                <p>
                    La Empresa <span class="nombre-empresa">CORPORACIÓN TURISMO H&H SAN PEDRO S.A.C.</span>, con RUC N°
                    {{ $company->number ?? '20605753176' }}, domicilio real en {{ $establishment->address }}, Distrito
                    de {{ $establishment->district->description }}, Provincia de
                    {{ $establishment->province->description }}, Departamento de
                    {{ $establishment->department->description }}, correo
                    electrónico <span class="email">{{ $establishment->email ?? 'hh.sanpedrosac@gmail.com' }}</span>,
                    con teléfono N° {{ $establishment->telephone ?? '984760460' }}, representado por el Gerente General
                    <span
                        class="nombre-empresa">{{ $solicitud->representante_legal_name ?? 'EDER PEDRO HIDALGO HILARIO' }}</span>.
                    Identificado con DNI N° {{ $company->representante_legal_dni ?? '44228036' }}, teléfono celular N°
                    {{ $company->representante_legal_phone ?? '984760460' }}, Partida Electrónica N°
                    {{ $solicitud->partida_electronica ?? '011284117' }}, con vigencia de poder y contando con las
                    facultades prevista para lo solicitado, ante Ud. En atenta forma digo:
                </p>

                <p>
                    Que, habiendo realizado el registro de una de la unidad perteneciente a nuestra empresa el cual por
                    error material de registrador solicito a su representada realizar la corrección de lo siguiente:
                </p>
            </div>
            <table class="tabla-correccion">
                <tr>
                    <th>DICE</th>
                    <th>DEBE DECIR</th>
                </tr>
                @if (isset($solicitud->detalle->first()->correcciones))
                    @foreach ($solicitud->detalle->first()->correcciones as $correccion)
                        <tr>
                            <td>{{ strtoupper($correccion['campo'] ?? '') }}:
                                {{ $correccion['valor_anterior'] ?? '' }}</td>
                            <td>{{ strtoupper($correccion['campo'] ?? '') }}: {{ $correccion['valor_nuevo'] ?? '' }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>

            <div class="conclusiones">
                <p><strong>POR LO EXPUESTO:</strong></p>
                <p>
                    Ruego a usted Sr. alcalde, acceder a mi petición, por considerarlo de justicia que espero alcanzar.
                </p>
            </div>

            <div class="adjunto">
                <p><strong>ADJUNTO:</strong></p>
                <p>1.-COPIA DE SOLICITUD DE REGISTRO</p>
            </div>
            <div style="margin-top: 1rem; margin-bottom: 1.5rem; text-align: right;width: 100%;">
                <p> {{ $establishment->district->description }},
                    {{ \App\Helpers\DateHelper::formatoEspanol($solicitud->fecha) }}
                </p>
            </div>
            <table class="full-width" style="margin-top: 2rem !important; width: 100%;">
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        <div style="text-align: center; margin-top: 0.5rem;margin-right: 1rem;">
                            @if ($company->img_firm)
                                <img src="data:{{ mime_content_type(public_path("{$img_firm}")) }};base64, {{ base64_encode(file_get_contents(public_path("{$img_firm}"))) }}"
                                    style="max-width: 150px; margin-right: 1rem;">
                            @else
                                <img src="https://placehold.co/150x50" alt="IMAGEN DE FIRMA">
                            @endif

                        </div>
                    </td>
                </tr>
            </table>

            <!-- Reservar espacio para el footer -->
            <div class="footer-space"></div>
        </div>
    </main>
</body>

</html>
