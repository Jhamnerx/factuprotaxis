<!DOCTYPE html>
<html lang="es">
@php
    $logo = "storage/uploads/logos/{$company->logo}";
    $img_firm = "storage/uploads/firms/{$company->img_firm}";

    $propietario = $vehiculo->propietario;

@endphp

<head>
    <meta charset="UTF-8">
    <title>Contrato de Sesión de Uso - {{ $vehiculo->placa }}</title>
    <style>
        {{ $stylesheet }} @page {
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
            /* Espacio reducido para el encabezado */
            margin-bottom: 1.2cm;
            /* Espacio reducido para el footer */
            text-align: justify;
            orphans: 2;
            /* Evita que queden menos de 2 líneas al final de una página */
            widows: 2;
            /* Evita que queden menos de 2 líneas al inicio de una página */
        }

        /* Contenido principal centrado */
        main {
            width: 100%;
            /* Ancho del contenido principal */
            max-width: 17cm;
            margin: 0 auto;
            /* Centrar el contenido horizontalmente */
            padding: 0 0.3cm;
            /* Padding reducido a los lados */
        }

        /* Estilos para párrafos */
        p {
            margin-bottom: 0.3cm;
            text-align: justify;
            page-break-inside: auto;
            /* Permite que los párrafos se dividan entre páginas */
        }

        /* Estilos para encabezados */
        h2,
        h3 {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            margin-top: 0.4cm;
            margin-bottom: 0.3cm;
        }

        /* Estilos para listas */
        ol,
        ul {
            padding-left: 0.8cm;
            margin-top: 0.1cm;
            margin-bottom: 0.2cm;
            page-break-inside: auto;
            /* Permite que las listas se dividan entre páginas */
        }

        /* Permite que los elementos de lista se dividan entre páginas */
        li {
            page-break-inside: auto;
            page-break-before: avoid;
            /* Evita un salto de página justo antes del elemento */
        }

        /* Estilos específicos para listas largas que necesitan dividirse correctamente */
        #obligaciones,
        #compromisos {
            page-break-inside: auto !important;
        }

        #obligaciones li,
        #compromisos li {
            page-break-inside: auto !important;
            margin-bottom: 0.2cm;
        }

        /* Espacio para asegurar que el contenido empiece después del header en cada página */
        .page-break {
            page-break-after: always;
        }

        /* Espacio para el header */
        .header-space {
            height: 0.1cm;
            display: block;
            margin-bottom: 0.2cm;
        }

        /* Espacio para asegurar que el contenido no se superponga con el footer */
        .footer-space {
            height: 1cm;
            display: block;
        }

        .company_logo_box_sm {
            height: 70px !important;
            width: 70px !important;
        }

        .center-logo {
            text-align: center;
            margin: 0 auto;
            display: block;
        }

        .company-name {
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            margin-top: 2px;
        }

        .center {
            text-align: center;
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
        <h2 style="text-align: center; margin-top: 0; font-size: 11px;">CONTRATO DE SESIÓN DE USO</h2>

        <p>Conste por el presente documento <b>CONTRATO DE SESIÓN DE USO, PARA LA PRESTACIÓN DEL SERVICIO DE TRANSPORTE
                REGULAR DE PERSONAS (TAXI)</b> que celebran de una parte <b>LA EMPRESA</b> DE TRANSPORTES,
            <b>{{ $company->name }} </b>, con RUC N° {{ $company->number }}, con domicilio real
            {{ $establishment->address }}, debidamente
            representado por Gerente General señor <b>{{ strtoupper($company->representante_legal_name) }}</b>
            identificado
            con DNI. N° {{ $company->representante_legal_dni }} a quien
            en
            adelante se le denominará <b>LA EMPRESA</b>; y de la otra parte, Don (ña) {{ $propietario->name }} con
            {{ $propietario->identity_document_type->description }}. Nº
            {{ $propietario->number }} con domicilio en {{ $propietario->address }}, Distrito de
            {{ $propietario->district ? $propietario->district->description : '' }}, Provincia
            de
            {{ $propietario->province ? $propietario->province->description : '' }}, a quien se
            le
            denominará <b>EL PROPIETARIO</b>, quienes acuerdan en los términos y condiciones siguientes:
        </p>

        <h3 style="margin-top: 0.3cm; margin-bottom: 0.2cm; font-size: 11px;">ANTECEDENTES</h3>
        <p><strong>PRIMERO.</strong> - <b>LA EMPRESA</b> se dedica a prestar el Servicio Público de Transporte Regular
            de
            Personas
            dentro de la Provincia de Huancayo en la modalidad de TAXI EMPRESA (vehículo Mayor) de acuerdo con las
            Resoluciones de Autorización Otorgada por la Autoridad competente en materia de transportes de la Provincia
            de
            Huancayo.</p>
        <p><strong>SEGUNDO.</strong> - <b>El PROPIETARIO</b> es una persona natural, propietario del Vehículo, en estado
            de
            buena
            conservación como para prestar el servicio de transporte público urbano de pasajeros (Taxi), con las
            siguientes
            características:
            Nº Interno: {{ $vehiculo->numero_interno }}, Placa de Rodaje N° {{ $vehiculo->placa }}, Categoría:
            {{ $vehiculo->categoria }}, Marca: {{ $vehiculo->marca ? $vehiculo->marca->nombre : '' }}, Año:
            {{ $vehiculo->year }}, Modelo:
            {{ $vehiculo->modelo ? $vehiculo->modelo->nombre : '' }}, Motor N°: {{ $vehiculo->numero_motor }},
            Color:
            {{ $vehiculo->color }}.
        </p>
        <p><strong>TERCERO.</strong> - <b>LA EMPRESA</b> requiere unidades para la conformación de la Flota Vehicular,
            para el
            cumplimiento de su objetivo empresarial, haciendo uso para tal efecto de las autorizaciones municipales que
            le
            permiten efectuar el servicio de transporte público de pasajeros dentro de la provincia de Huancayo y para
            tal
            efecto conviene con <b>EL PROPIETARIO</b> con el objeto de que su unidad vehicular sirva en este cometido en
            calidad de comisionista. </p>

        <h3 style="margin-top: 0.3cm; margin-bottom: 0.2cm; font-size: 11px;">DE LAS OBLIGACIONES DE <b> EL
                PROPIETARIO</b>
        </h3>
        <p><strong>CUARTO.</strong> - <b>EL PROPIETARIO</b> deberá cumplir con las siguientes obligaciones:</p>
        <ol id="obligaciones">
            <li>Cumplir con el horario y conforme a las disipaciones legales emitidas por las autoridades competentes.
            </li>
            <li>Cumplir con los pagos de tributos, derechos y demás obligaciones que sean fijadas por <b>LA EMPRESA</b>.
            </li>
            <li>Cumplir con las normas legales: “Reglamento Nacional Tránsito”, Reglamento Nacional de Administración de
                Transporte” y Reglamento Interno de <b>LA EMPRESA</b> y demás dispositivos que emane de la autoridad
                competente.
            </li>
            <li>Comunicar cualquier modificación de las características de su unidad, transferencia de su propiedad,
                medida
                judicial o extrajudicial que recaiga en esta y en general cualquier ocurrencia que se dé, respecto al
                vehículo que presta servicio por intermedio del presente contrato dentro de las 24 horas de ocurrida la
                misma.</li>
            <li>La unidad motorizada de <b>EL PROPIETARIO</b> deberá contar con un SOAT y/o CAT y CITV vigente al
                momento de su
                operación.</li>
            <li>El conductor, que <b>EL PROPIETARIO Y/O LA EMPRESA</b> contrate, deberán contar con los
                documentos de reglamento como son: (DNI, Licencia de Conducir cuya categoría sea la que corresponda al
                tipo de vehículo que
                conduce,
                Carnet de habilitación de conductor, otorgada por la autoridad competente.</li>
            <li>Comunicar de manera inmediata a la Gerencia de <b>LA EMPRESA</b> de cualquier incidente que ocurra
                durante su
                operación (atropello, imposición de multa u otros análogos) with el objeto de la asesoría del caso.</li>
            <li><b>EL PROPIETARIO</b> está en la obligación de comunicar al conductor respecto de las directivas y
                obligaciones
                que
                comunique en <b>LA EMPRESA</b>, siendo responsable solidariamente con ellos, respecto de las conductas
                que
                omitan o
                infrinjan a lo dispuesto por las normas establecidas.</li>
            <li><b>EL PROPIETARIO</b> es únicamente responsable de las acciones que conlleven daños o perjuicios a
                terceros, si
                estas se han originado en inobservancia de los reglamentos y leyes de la materia.</li>
            <li><b>EL PROPIETARIO</b> al vender su vehículo a tercera persona, será excluido del servicio de <b>la
                    EMPRESA</b>
                dándose
                de
                baja el vehículo del Padrón correspondiente y siendo sustituido por otro que designe <b>la Empresa</b>.
            </li>
            <li><b>EL PROPIETARIO</b> asumirá las obligaciones de carácter remunerativas que contraigan sus conductores
                u otras
                personas que operen su unidad vehicular. </li>
        </ol>

        <h3 style="margin-top: 0.3cm; margin-bottom: 0.2cm; font-size: 11px;">DE LOS PAGOS POR TRIBUTOS A <b>LA
                EMPRESA</b>
        </h3>
        <p><strong>QUINTO.</strong> - <b>EL PROPIETARIO</b> deberá cumplir con las siguientes obligaciones:</p>
        <ol>
            <li>Pagos de tributo Mensual de TREINTA SOLES 30.00, en caso de no cumplir <b>la empresa</b> realizara el
                retiro de
                la
                unidad por falta de pago de tributo y Resolución de Contrato; pudiendo aplicarse las misma en forma
                indistinta.</li>
        </ol>

        <h3 style="margin-top: 0.3cm; margin-bottom: 0.2cm; font-size: 11px;">DEL COMPROMISO D<b>EL PROPIETARIO</b></h3>
        <p><strong>SEXTO.</strong> - El compromiso expreso d<b>el PROPIETARIO</b> radica en:</p>
        <ol id="compromisos">
            <li>Se compromete a prestar el servicio regular de personas (taxi) bajo su responsabilidad directa y en
                forma
                regular y exclusiva, de acuerdo con sus obligaciones señaladas en el punto anterior por el plazo que
                establece el presente contrato, para ese efecto su unidad motorizada revestirá de las características
                técnicas de identificación que lo relacione a <b>LA EMPRESA</b>.</li>
            <li>Se compromete a título personal y se responsabiliza directamente por el accionar de su conductor que
                cometan actos que conlleven a conflictos entre el personal: directivos, choferes, controladores o contra
                la
                propia EMPRESA, constituyendo este acto causal de resolución automática del presente contrato, previa
                notificación de este.</li>
            <li>Se compromete a pagar el monto dinerario por concepto de TRIBUTO en forma diaria y como mínimo 25 días
                al
                mes, pudiendo extenderse la exoneración del pago por un lapso de 4 días más, previa verificación y/o
                evaluación que efectúe el Gerente de <b>la Empresa</b> o exista razones atendibles previa justificación,
                el
                mismo
                que le da el derecho de poder operar en el servicio de transporte público de pasajeros; además de los
                pagos
                extraordinarios por alguna actividad social empresarial previo aviso por parte de la Gerencia, cuyos
                montos
                serán fijados y comunicadas en su oportunidad al comisionista. </li>
            <li>Se compromete a ofrecer como garantía el vehículo cuyas características se identifican en la Cláusula
                Segunda del presente contrato, para los efectos a asumir su responsabilidad civil frente a terceros, en
                tanto y en cuanto de su operación o explotación como tal resultase algún incidente que cause daños o
                perjuicios a terceros incluyendo a la misma EMPRESA. Reservándose para tal efecto <b>LA EMPRESA</b> la
                formalización de la garantía o el mejoramiento de esta para asegurar cualquiera de las obligaciones de
                cargo
                del COMISIONISTA o de sus choferes que pudiera afectar directa o indirectamente a terceros o a la propia
                EMPRESA.</li>
            <li>Se compromete asumir los costos por el concepto de Certificado de Habilitación Vehicular, Constatación
                de
                Características, Baja de Vehículo, Inclusión de Vehículo u otros procedimientos análogos que establezca
                para
                este efecto la Autoridad Municipalidad Provincial que corresponda, hecho que permitirá la regular
                prestación
                del servicio por la unidad motorizada de propiedad del COMISIONISTA.</li>
            <li><b>EL PROPIETARIO</b> sólo podrá reemplazar su unidad motorizada que figura en el presente contrato,
                siempre en
                cuando sea de su dominio a título de propietario y en tanto sus condiciones operativas sean óptimas para
                el
                servicio y previa autorización de <b>la Empresa</b>. Tendrá 25 días de plazo, a partir de su venta y/o
                dejar de
                trabajar el vehículo que figura en el padrón, de lo contrario perderá su línea. </li>
        </ol>

        <h3 style="margin-top: 0.3cm; margin-bottom: 0.2cm; font-size: 11px;">VIGENCIA DEL CONTRATO</h3>
        <p><strong>SÉPTIMO.</strong> - La vigencia del presente contrato rige a partir de la fecha de su celebración por
            TIEMPO INDETERMINADO, pudiendo ser renovado únicamente mediante la suscripción de un nuevo contrato.</p>

        <h3 style="margin-top: 0.3cm; margin-bottom: 0.2cm; font-size: 11px;">DE LAS SANCIONES</h3>
        <p><strong>OCTAVO.</strong> - <b>LA EMPRESA</b> podrá imponer a <b>EL PROPIETARIO</b> las siguientes sanciones
            por el
            incumplimiento del presente contrato e independientemente de lo establecido por el Reglamento Interno de la
            EMPRESA: </p>
        <ol>
            <li>Recomendación</li>
            <li>Amonestación</li>
            <li>Suspensión</li>
            <li>Retiro de <b>la empresa</b> por falta de pago de tributo y Resolución de Contrato; pudiendo aplicarse
                las misma
                en
                forma indistinta, según sea considerado la gravedad de los hechos. </li>
        </ol>
        <p><strong><i>NOVENO.</i></strong> - Las partes contratantes para efectos del presente contrato renuncian al
            fuero de sus
            jurisdicciones, sometiéndose expresamente a la jurisdicción de los jueces y tribunales de la provincia de
            Huancayo, según sea. </p>

        <div style="margin-top: 1rem; margin-bottom: 0.5rem; text-align: right;">
            <p> {{ $establishment->district->description }}, {{ \App\Helpers\DateHelper::formatoEspanol(now()) }}</p>
        </div>

        <table class="full-width" style="margin-top: 2rem !important; width: 100%;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <div style="text-align: center;">
                        <b>LA EMPRESA</b>
                    </div>
                    <div style="text-align: center; margin-top: 0.5rem;margin-right: 1rem;">
                        <img src="data:{{ mime_content_type(public_path("{$img_firm}")) }};base64, {{ base64_encode(file_get_contents(public_path("{$img_firm}"))) }}"
                            style="max-width: 150px; margin-right: 1rem;">
                    </div>
                </td>

                <td style="width: 50%; vertical-align: top;">
                    <div style="text-align: center;">
                        <b>EL PROPIETARIO</b>
                    </div>
                    <div style="text-align: center; margin-top: 2rem;">
                        <div style="border-top: 1px solid #000; width: 70%; margin: 0 auto;"></div>
                        <div style="margin-top: 0.3rem;">{{ $propietario->name }}</div>
                        <div>{{ $propietario->identity_document_type->description }} N° {{ $propietario->number }}
                        </div>
                        <div>PLACA: {{ $vehiculo->placa }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Reservar espacio para el footer -->
        <div class="footer-space"></div>
    </main>

</body>

</html>
