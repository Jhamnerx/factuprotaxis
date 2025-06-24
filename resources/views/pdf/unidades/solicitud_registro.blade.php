<!DOCTYPE html>
<html lang="es">
@php
    $logo = "storage/uploads/logos/{$company->logo}";
    $img_firm = "storage/uploads/firms/{$company->img_firm}";
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Registro</title>
    <style>
        {!! $stylesheet !!} @page {
            margin-top: 0.5cm;
            margin-bottom: 1.2cm;
            margin-left: 1.5cm;
            margin-right: 1.5cm;
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
            height: 1cm;
            text-align: center;
            padding-top: 2px;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 0.8cm;
            text-align: center;
            font-size: 7px;
            padding-top: 3px;
            border-top: 1px solid #ccc;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            line-height: 1.3;
            position: relative;
            margin-top: 1.5cm;
            margin-bottom: 1cm;
        }

        /* Contenido principal centrado */
        main {
            width: 100%;
            max-width: 19cm;
            margin: 0 auto;
            padding: 0 0.2cm;
        }

        /* Espacio para el header */
        .header-space {
            height: 0.1cm;
            display: block;
        }

        /* Espacio para el footer */
        .footer-space {
            height: 1cm;
            display: block;
        }

        .titulo {
            font-weight: bold;
            text-align: center;
            margin: 0.2cm 0;
            text-transform: uppercase;
            font-size: 12px;
            border-bottom: 1px solid #444;
            padding-bottom: 0.1cm;
        }

        /* Tablas y layout */
        .table-container {
            width: 100%;
            margin-bottom: 0.3cm;
        }

        .seccion-datos {
            margin-bottom: 0.3cm;
        }

        .datos-empresa {
            width: 100%;
            border-collapse: collapse;
        }

        .seccion {
            margin-bottom: 0.3cm;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .seccion-titulo {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 0.1cm;
            background-color: #eaeaea;
            padding: 0.1cm 0.2cm;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
        }

        .datos {
            width: 100%;
            border-collapse: collapse;
        }

        .datos td {
            padding: 1px 3px;
            vertical-align: top;
            border-bottom: 1px dotted #ddd;
        }

        .label {
            width: 30%;
            font-weight: bold;
        }

        .value {
            width: 70%;
        }

        /* Layout vertical para datos consecutivos */
        .flex-container {
            width: 100%;
            margin-bottom: 0.3cm;
        }

        .texto-solicitud {
            background-color: #f4f4f4;
            padding: 0.2cm;
            border-radius: 4px;
            margin: 0.2cm 0;
            font-style: italic;
            text-align: justify;
            font-size: 8px;
        }

        /* Sección inferior con firma y adjuntos */
        .bottom-section {
            width: 100%;
            margin-top: 0.2cm;
            display: table;
        }

        .firma {
            width: 40%;
            display: table-cell;
            vertical-align: top;
            text-align: left;
        }

        .adjuntos {
            width: 60%;
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

        .fecha {
            text-align: left;
            margin: 0.2cm 0 0.5cm 0;
            /* Añado margen inferior de 0.5cm */
            font-size: 10px;
            /* Ajustado al tamaño base del documento */
        }

        .firma img {
            max-width: 130px;
            /* Aumentado de 100px a 130px */
            max-height: 40px;
            /* Aumentado de 30px a 40px */
            margin: 0;
            display: block;
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
        <div class="titulo">
            SOLICITUD DE REGISTRO DE UNIDAD<br>
            <span style="font-size: 8px;">MUNICIPALIDAD PROVINCIAL DE HUANCAYO</span>
        </div>

        <div class="flex-container">
            <div class="seccion">
                <div class="seccion-titulo">I. DATOS DEL SOLICITANTE</div>
                <table class="datos">
                    <tr>
                        <td class="label">RUC</td>
                        <td class="value">: {{ $empresa->ruc ?? '20605753176' }}</td>
                    </tr>
                    <tr>
                        <td class="label">CÓDIGO</td>
                        <td class="value">: {{ $empresa->codigo ?? 'ST-0160' }}</td>
                    </tr>
                    <tr>
                        <td class="label">RAZÓN SOCIAL</td>
                        <td class="value">:
                            {{ $empresa->razon_social ?? 'CORPORACIÓN TURISMO H&H SAN PEDRO S.A.C.' }}</td>
                    </tr>
                    <tr>
                        <td class="label">DOMICILIO</td>
                        <td class="value">: {{ $empresa->direccion ?? 'JR LAS MAGNOLIAS #523' }}</td>
                    </tr>
                    <tr>
                        <td class="label">PARTIDA REG.</td>
                        <td class="value">: {{ $empresa->partida_registral ?? '11284117' }}</td>
                    </tr>
                </table>
            </div>

            <div class="seccion">
                <div class="seccion-titulo">II. REPRESENTANTE LEGAL</div>
                <table class="datos">
                    <tr>
                        <td class="label">NOMBRE</td>
                        <td class="value">: {{ $representante->nombre ?? 'Eder Pedro HIDALGO HILARIO' }}</td>
                    </tr>
                    <tr>
                        <td class="label">DNI</td>
                        <td class="value">: {{ $representante->dni ?? '44228036' }}</td>
                    </tr>
                    <tr>
                        <td class="label">CELULAR</td>
                        <td class="value">: {{ $representante->telefono ?? '984 760 460' }}</td>
                    </tr>
                    <tr>
                        <td class="label">CORREO</td>
                        <td class="value">: {{ $representante->email ?? 'ehidalgohi04@gmail.com' }}</td>
                    </tr>
                </table>
            </div>

            <div class="seccion">
                <div class="seccion-titulo">III. CARACTERÍSTICA DE UNIDAD</div>
                <table class="datos">
                    <tr>
                        <td class="label">N° FLOTA</td>
                        <td class="value">: {{ $unidad->flota ?? '433' }}</td>
                    </tr>
                    <tr>
                        <td class="label">PLACA</td>
                        <td class="value">: {{ $unidad->placa ?? 'W5D-618' }}</td>
                    </tr>
                    <tr>
                        <td class="label">PROPIETARIO</td>
                        <td class="value">: {{ $unidad->propietario ?? 'Jacquelin Marisol GUEVARA ROJAS' }}</td>
                    </tr>
                    <tr>
                        <td class="label">CATEGORÍA</td>
                        <td class="value">: {{ $unidad->categoria ?? 'M1' }}</td>
                    </tr>
                    <tr>
                        <td class="label">MARCA</td>
                        <td class="value">: {{ $unidad->marca ?? 'KIA' }}</td>
                    </tr>
                    <tr>
                        <td class="label">MODELO</td>
                        <td class="value">: {{ $unidad->modelo ?? 'SOLUTO' }}</td>
                    </tr>
                    <tr>
                        <td class="label">AÑO</td>
                        <td class="value">: {{ $unidad->anio ?? '2023' }}</td>
                    </tr>
                    <tr>
                        <td class="label">MOTOR N°</td>
                        <td class="value">: {{ $unidad->motor ?? 'G4LCNJ039954' }}</td>
                    </tr>
                    <tr>
                        <td class="label">COLOR</td>
                        <td class="value">: {{ $unidad->color ?? 'BLANCO CLARO' }}</td>
                    </tr>
                    <tr>
                        <td class="label">PESO</td>
                        <td class="value">: {{ $unidad->peso ?? '1.036' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="texto-solicitud">
            Por lo expuesto, sírvase Ud. Sr. Alcalde ordenar por quien corresponde la atención de lo solicitado para el
            registro
            de la unidad vehicular en el sistema de transporte de la Municipalidad Provincial de Huancayo.
        </div>
        <div class="bottom-section">
            <div class="firma">
                <div class="fecha">
                    {{ $establishment->district->description }},
                    {{ \App\Helpers\DateHelper::formatoEspanol($solicitud->fecha) }}
                </div>
                <img
                    src="data:{{ mime_content_type(public_path("{$img_firm}")) }};base64, {{ base64_encode(file_get_contents(public_path("{$img_firm}"))) }}">
            </div>
            <div class="adjuntos">
                <div class="seccion-titulo" style="font-size: 8px; padding: 1px 2px;">DOCUMENTOS ADJUNTOS</div>
                <ul>
                    <li>Copia de Tarjeta de Propiedad</li>
                    <li>Copia de DNI</li>
                    <li>Copia de CITV</li>
                    <li>Copia de SOAT</li>
                    <li>Contrato de Afiliación</li>
                    <li>Declaración Jurada</li>
                </ul>
            </div>
        </div>

        <div class="footer-space"></div>
    </main>
</body>

</html>
