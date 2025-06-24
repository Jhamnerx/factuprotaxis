<!DOCTYPE html>
<html lang="es">
@php
    $logo = "storage/uploads/logos/{$company->logo}";
    $img_firm = "storage/uploads/firms/{$company->img_firm}";
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autorización de Viaje</title>
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
            height: 1.5cm;
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
        }

        .paragraph {
            margin: 15px 0;
            text-align: justify;
            line-height: 1.5;
            font-size: 12px;
        }

        .bold {
            font-weight: bold;
        }

        .section-number {
            font-weight: bold;
            margin-right: 5px;
        }

        .family-list {
            margin: 15px 30px;
            line-height: 2;
        }

        .family-item {
            margin-bottom: 10px;
            position: relative;
            padding-left: 20px;
        }

        .check-mark {
            position: absolute;
            left: 0;
            top: 2px;
        }

        .dni-line {
            display: inline;
            border-bottom: 1px solid #000;
            min-width: 100px;
            text-align: center;
            margin-left: 10px;
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

        .img-signature {
            max-width: 150px;
            max-height: 70px;
            margin: 0 auto;
            display: block;
        }

        .company-signature {
            font-weight: bold;
            margin-bottom: 5px;
            text-align: center;
            text-transform: uppercase;
            font-size: 12px;
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

        .underline {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 200px;
            text-align: center;
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
            RUC: {{ $company->number }} | {{ $establishment->address }} | {{ $establishment->email }}
            @if ($establishment->telephone)
                | Tel: {{ $establishment->telephone }}
            @endif
        </div>
    </footer>

    <!-- Este div mantiene el espacio adecuado entre el header y el contenido en todas las páginas -->
    <div class="header-space"></div>
    <main>
        <div class="title">AUTORIZACIÓN DE VIAJE DE VEHÍCULO</div>

        <div class="paragraph">
            La EMPRESA <span class="bold">{{ $company->name }}</span>, representado
            por su Gerente General <span class="bold">{{ $establishment->representative_name }}</span>, identificado
            con DNI
            N° {{ $establishment->district_id }}, con dirección {{ $establishment->address }} y en
            sus facultades expuestas en la vigencia de Poder vigente a la fecha <span class="bold">AUTORIZA:</span>
        </div>

        <div class="paragraph">
            <span class="section-number">01.</span>
            Al vehículo de placa N° <span class="bold">{{ $permiso->datosVehiculo->placa }}</span>,
            con N° de Flota {{ $permiso->datosVehiculo->numero_interno }}
            de propiedad de <span class="bold">
                @if (isset($permiso->propietario['name']))
                    {{ $permiso->propietario['name'] }}
                @else
                    {{ $permiso->datosVehiculo->propietario->name ?? 'N/A' }}
                @endif
            </span>
            a realizar el viaje a la ciudad de <span class="underline">{{ $permiso->motivo }}</span>, con familiares:
        </div>

        <div class="family-list">
            @if (isset($permiso->personas_autorizadas) && is_array($permiso->personas_autorizadas))
                @foreach ($permiso->personas_autorizadas as $persona)
                    <div class="family-item">
                        <span class="check-mark">✓</span>
                        <span>{{ $persona['nombre'] ?? '' }}</span>
                        con DNI N° <span class="dni-line">{{ $persona['dni'] ?? '' }}</span>
                    </div>
                @endforeach
            @else
                <div class="family-item">
                    <span class="check-mark">✓</span>
                    <span>______________________________________________________________</span>
                    con DNI N° <span class="dni-line">_______________</span>
                </div>
                <div class="family-item">
                    <span class="check-mark">✓</span>
                    <span>______________________________________________________________</span>
                    con DNI N° <span class="dni-line">_______________</span>
                </div>
                <div class="family-item">
                    <span class="check-mark">✓</span>
                    <span>______________________________________________________________</span>
                    con DNI N° <span class="dni-line">_______________</span>
                </div>
                <div class="family-item">
                    <span class="check-mark">✓</span>
                    <span>______________________________________________________________</span>
                    con DNI N° <span class="dni-line">_______________</span>
                </div>
            @endif
        </div>

        <div class="paragraph">
            <span class="section-number">02.</span> Asimismo, el retorno será al término de sus actividades.
        </div>

        <div class="paragraph">
            Lo que se expide el presente documento para los fines pertinentes.
        </div>

        <div class="footer-date">
            {{ $establishment->district->description }},
            {{ \App\Helpers\DateHelper::formatoEspanol($permiso->fecha_inicio) }}.
        </div>

        <div class="signature-section">
            <div style="text-align: center; margin: 40px auto 10px auto;">
                <div class="company-signature">{{ $company->name }}</div>
                <div class="company-signature" style="margin-bottom: 30px;">{{ $company->trade_name }}</div>

                <div style="margin-bottom: 5px;">
                    <div style="width: 7cm; border-top: 1px solid #000; margin: 0 auto;"></div>
                </div>

                <div class="signature-name">{{ $establishment->representative_name }}</div>
                <div class="signature-title">Gerente General</div>
            </div>
        </div>

        <!-- Reservar espacio para el footer -->
        <div class="footer-space"></div>
    </main>
</body>

</html>
