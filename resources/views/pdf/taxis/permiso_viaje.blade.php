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
            height: 2cm;
            text-align: center;
            padding-top: 8px;
            border-bottom: 1px solid #e0e0e0;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 1cm;
            text-align: center;
            font-size: 8px;
            padding-top: 6px;
            border-top: 1px solid #666;
            color: #555;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            position: relative;
            margin-top: 2.2cm;
            margin-bottom: 1.2cm;
            text-align: justify;
            orphans: 2;
            widows: 2;
            color: #222;
            background-color: #ffffff;
        }

        /* Contenido principal centrado */
        main {
            width: 100%;
            max-width: 17cm;
            margin: 0 auto;
            padding: 0 0.5cm;
            background-color: #ffffff;
        }

        /* Espacio para el header */
        .header-space {
            height: 0.1cm;
            display: block;
            margin-bottom: 0.5cm;
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

        .company_logo {
            max-width: 100px;
            margin: 0 auto;
            display: block;
            filter: drop-shadow(0px 1px 1px rgba(0, 0, 0, 0.1));
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 1cm 0 0.5cm 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #000;
            border-bottom: 0;
            padding-bottom: 5px;
        }

        .paragraph {
            margin: 18px 0;
            text-align: justify;
            line-height: 1.7;
            font-size: 12px;
        }

        .bold {
            font-weight: bold;
        }

        .section-number {
            font-weight: bold;
            margin-right: 10px;
            color: #000;
            font-size: 13px;
        }

        .family-list {
            margin: 22px 15px 22px 30px;
            line-height: 2;
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 3px solid #e0e0e0;
            border-radius: 3px;
        }

        .family-item {
            margin-bottom: 14px;
            position: relative;
            padding-left: 25px;
            font-size: 11px;
        }

        .check-mark {
            position: absolute;
            left: 0;
            top: 2px;
            font-weight: bold;
            color: #000;
        }

        .dni-info {
            font-weight: bold;
            display: inline-block;
            min-width: 110px;
            margin-right: 15px;
            color: #333;
        }

        .name-info {
            display: inline;
            color: #333;
        }

        .footer-date {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
            font-style: italic;
        }

        .signature-section {
            margin-top: 60px;
            text-align: center;
        }

        .img-signature {
            max-width: 180px;
            max-height: 70px;
            margin: 0 auto 10px auto;
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
            color: #555;
            letter-spacing: 1px;
        }

        /* Estilo para destacar información importante */
        .destacado {
            color: #000;
            font-weight: bold;
            background-color: #f4f4f4;
            padding: 0 3px;
            border-radius: 2px;
        }

        /* Estilos para la tabla de personas autorizadas */
        .family-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 22px 0;
            background-color: #fcfcfc;
            border-left: 3px solid #e0e0e0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .family-table tr {
            border-bottom: 1px solid #f0f0f0;
        }

        .family-table tr:last-child {
            border-bottom: none;
        }

        .family-table td {
            padding: 10px 5px;
            vertical-align: middle;
            line-height: 1.5;
            font-size: 11px;
        }

        .check-cell {
            width: 30px;
            text-align: center;
            font-weight: bold;
            color: #000;
            padding-left: 15px;
        }

        .dni-cell {
            width: 120px;
            font-weight: bold;
            color: #333;
        }

        .name-cell {
            color: #333;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">
            @if ($company->logo)
                <img src="data:{{ mime_content_type(public_path("{$logo}")) }};base64, {{ base64_encode(file_get_contents(public_path("{$logo}"))) }}"
                    alt="{{ $company->name }}" class="company_logo">
            @else
                <img src="{{ asset('logo/tulogo.png') }}" alt="{{ $company->name }}" class="company_logo">
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
        <div style="text-align: center; margin-bottom: 1cm;">
            <div class="title">AUTORIZACIÓN DE VIAJE DE VEHÍCULO</div>
            <div style="width: 80%; border-bottom: 1px solid #444; margin: 0 auto 10px auto;"></div>
            <div style="width: 50%; border-bottom: 2px solid #888; margin: 0 auto 20px auto;"></div>
        </div>

        <div class="paragraph">
            La EMPRESA <span class="bold">{{ $company->name }}</span>, representada
            por su Gerente General <span class="bold">{{ $establishment->representative_name }}</span>, identificado
            con DNI N° <span class="destacado">{{ $establishment->district_id }}</span>, con dirección
            {{ $establishment->address }} y en sus facultades expuestas en la vigencia
            de Poder vigente a la fecha <span class="bold">AUTORIZA:</span>
        </div>

        <div class="paragraph">
            <span class="section-number">01.</span>
            Al vehículo de placa N° <span class="destacado">{{ $permiso->datosVehiculo->placa }}</span>,
            con N° de Flota <span class="bold">{{ $permiso->datosVehiculo->numero_interno }}</span>
            de propiedad de <span class="bold">
                @if (isset($permiso->propietario['name']))
                    {{ $permiso->propietario['name'] }}
                @else
                    {{ $permiso->datosVehiculo->propietario->name ?? 'N/A' }}
                @endif
            </span>
            a realizar el viaje a la ciudad de {{ $permiso->motivo }}, con familiares:
        </div>

        <table class="family-table">
            @if (isset($permiso->personas_autorizadas) && is_array($permiso->personas_autorizadas))
                @foreach ($permiso->personas_autorizadas as $persona)
                    <tr>
                        <td class="check-cell">✓</td>
                        <td class="dni-cell">DNI {{ $persona['documento'] ?? '' }}</td>
                        <td class="name-cell">{{ $persona['nombre'] ?? '' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="check-cell">✓</td>
                    <td class="dni-cell">DNI __________</td>
                    <td class="name-cell">__________________________________________________</td>
                </tr>
                <tr>
                    <td class="check-cell">✓</td>
                    <td class="dni-cell">DNI __________</td>
                    <td class="name-cell">__________________________________________________</td>
                </tr>
                <tr>
                    <td class="check-cell">✓</td>
                    <td class="dni-cell">DNI __________</td>
                    <td class="name-cell">__________________________________________________</td>
                </tr>
                <tr>
                    <td class="check-cell">✓</td>
                    <td class="dni-cell">DNI __________</td>
                    <td class="name-cell">__________________________________________________</td>
                </tr>
            @endif
        </table>

        <div class="paragraph">
            <span class="section-number">02.</span> Asimismo, el retorno será al término de sus actividades.
        </div>

        <div class="paragraph" style="margin-top: 25px;">
            Lo que se expide el presente documento para los fines pertinentes.
        </div>

        <div class="footer-date">
            {{ $establishment->district->description }},
            {{ \App\Helpers\DateHelper::formatoEspanol($permiso->fecha_inicio) }}
        </div>

        <div class="signature-section">
            <div style="text-align: center; margin: 60px auto 10px auto;">

                @if ($company->img_firm)
                    <div style="margin-bottom: 15px;">
                        <img src="data:{{ mime_content_type(public_path("{$img_firm}")) }};base64, {{ base64_encode(file_get_contents(public_path("{$img_firm}"))) }}"
                            alt="Firma" class="img-signature">
                    </div>
                @endif

            </div>
        </div>

        <!-- Reservar espacio para el footer -->
        <div class="footer-space"></div>
    </main>
</body>

</html>
