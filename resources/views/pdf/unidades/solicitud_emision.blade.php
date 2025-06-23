<!DOCTYPE html>
<html lang="es">
@php
    $logo = "storage/uploads/logos/{$company->logo}";
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Emisión de Tarjeta Única de Circulación</title>
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

        .logo {
            text-align: left;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 150px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
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

        .tabla-unidades {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .tabla-unidades th,
        .tabla-unidades td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        .tabla-unidades th {
            background-color: #f2f2f2;
        }

        .conclusion {
            text-align: justify;
            margin: 20px 0;
            font-weight: bold;
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
        <div class="container">
            <div class="sumilla">
                SOLICITO EMISIÓN DE TARJETA ÚNICA DE CIRCULACIÓN.
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
                    {{ $company->number ?? '20605753176' }}, domicilio real en Jr. Las Magnolias N° 523, Distrito de El
                    Tambo, Provincia de Huancayo, Departamento de Junín, correo electrónico <span
                        class="email">{{ $company->email ?? 'hh.sanpedrosac@gmail.com' }}</span>, con teléfono N°
                    {{ $company->telephone ?? '984760460' }}, representado por el Gerente General <span
                        class="nombre-empresa">{{ $solicitud->representante_nombre ?? 'EDER PEDRO HIDALGO HILARIO' }}</span>.
                    Identificado con DNI N° {{ $solicitud->representante_dni ?? '44228036' }}, teléfono celular N°
                    {{ $solicitud->representante_celular ?? '984760460' }}, Partida Electrónica N°
                    {{ $solicitud->partida_electronica ?? '011284117' }}, con vigencia de poder y contando con las
                    facultades prevista para lo solicitado, ante Ud. En atenta forma digo:
                </p>
                <p>
                    Que, habiendo cumplido con los requisitos requeridos por su representada para la emisión de Tarjeta
                    Única de Circulación, solicito se imprima y se haga entrega del TUC, a fin de evitar perjuicio a los
                    conductores de la <span class="nombre-empresa">CORPORACIÓN TURISMO H&H SAN PEDRO S.A.C.</span> de
                    las
                    Unidades:
                </p>
            </div>

            <table class="tabla-unidades">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>FLOTA</th>
                        <th>PLACA</th>
                        <th>CC N°</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($solicitud->unidades ?? [] as $index => $unidad)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $unidad->flota }}</td>
                            <td>{{ $unidad->placa }}</td>
                            <td>{{ $unidad->cc_numero }}</td>
                        </tr>
                    @endforeach
                    @if (empty($solicitud->unidades))
                        <!-- Datos de ejemplo -->
                        <tr>
                            <td>1</td>
                            <td>07</td>
                            <td>F5O-639</td>
                            <td>012373</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>108</td>
                            <td>W3Q-410</td>
                            <td>0123556</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>03</td>
                            <td>W3O-255</td>
                            <td>012362</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>84</td>
                            <td>A1G-108</td>
                            <td>011253</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>88</td>
                            <td>F6H-341</td>
                            <td>011252</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>59</td>
                            <td>BAF-470</td>
                            <td>011426</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>36</td>
                            <td>C0U-203</td>
                            <td>012368</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>99</td>
                            <td>W4Q-131</td>
                            <td>012376</td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>535</td>
                            <td>ANV-427</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>117</td>
                            <td>D2G-538</td>
                            <td>012357</td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>127</td>
                            <td>B0Y-639</td>
                            <td>012379</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="conclusion">
                POR LO EXPUESTO:
            </div>

            <div class="cuerpo">
                <p>
                    Ruego a usted Sr. alcalde, acceder a mi petición, por considerarlo de justicia que espero alcanzar.
                </p>
            </div>
            <div class="fecha">
                {{ $establishment->district }}, {{ date('d') }} de
                {{ \App\Helpers\DateHelper::formatoEspanol(now(), false, true) }}
            </div>

            <div class="firma">
                <div style="border-top: 1px solid #000; width: 250px; margin: 0 auto;"></div>
                <div style="margin-top: 5px;">{{ $company->name }}</div>
                <div>{{ $company->trade_name }}</div>
                <div>RUC: {{ $company->number }}</div>
            </div>

            <!-- Reservar espacio para el footer -->
            <div class="footer-space"></div>
        </div>
    </main>
</body>

</html>
