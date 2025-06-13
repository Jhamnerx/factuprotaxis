<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            font-weight: normal;
        }

        .info-container {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
        }

        .signature-box {
            border-top: 1px solid #000;
            display: inline-block;
            padding-top: 5px;
            width: 200px;
            margin-top: 70px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>SOLICITUD DE {{ strtoupper($solicitud->getTipoTexto()) }}</h1>
        <h2>N° {{ str_pad($solicitud->id, 6, '0', STR_PAD_LEFT) }}</h2>
        <p>Fecha: {{ $solicitud->fecha->format('d/m/Y') }}</p>
    </div>

    <div class="info-container">
        <h3>INFORMACIÓN DE LA SOLICITUD</h3>
        <table>
            <tr>
                <th>Tipo de Solicitud</th>
                <td>{{ $solicitud->getTipoTexto() }}</td>
                <th>Estado</th>
                <td>{{ $solicitud->getEstadoTexto() }}</td>
            </tr>
            <tr>
                <th>Fecha</th>
                <td>{{ $solicitud->fecha->format('d/m/Y') }}</td>
                <th>Usuario</th>
                <td>{{ $solicitud->user->name }}</td>
            </tr>
            <tr>
                <th>Motivo</th>
                <td colspan="3">{{ $solicitud->motivo }}</td>
            </tr>
            <tr>
                <th>Descripción</th>
                <td colspan="3">{{ $solicitud->descripcion }}</td>
            </tr>
            <tr>
                <th>Observaciones</th>
                <td colspan="3">{{ $solicitud->observaciones }}</td>
            </tr>
        </table>
    </div>

    <div class="info-container">
        <h3>INFORMACIÓN DEL VEHÍCULO</h3>
        <table>
            <tr>
                <th>Placa</th>
                <td>{{ isset($solicitud->vehiculo['placa']) ? $solicitud->vehiculo['placa'] : 'N/A' }}</td>
                <th>Marca</th>
                <td>{{ isset($solicitud->vehiculo['marca']) ? $solicitud->vehiculo['marca'] : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Modelo</th>
                <td>{{ isset($solicitud->vehiculo['modelo']) ? $solicitud->vehiculo['modelo'] : 'N/A' }}</td>
                <th>Año</th>
                <td>{{ isset($solicitud->vehiculo['anio']) ? $solicitud->vehiculo['anio'] : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Color</th>
                <td>{{ isset($solicitud->vehiculo['color']) ? $solicitud->vehiculo['color'] : 'N/A' }}</td>
                <th>N° Motor</th>
                <td>{{ isset($solicitud->vehiculo['num_motor']) ? $solicitud->vehiculo['num_motor'] : 'N/A' }}</td>
            </tr>
            <tr>
                <th>N° Chasis</th>
                <td>{{ isset($solicitud->vehiculo['num_chasis']) ? $solicitud->vehiculo['num_chasis'] : 'N/A' }}</td>
                <th>N° Serie</th>
                <td>{{ isset($solicitud->vehiculo['num_serie']) ? $solicitud->vehiculo['num_serie'] : 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="info-container">
        <h3>INFORMACIÓN DEL PROPIETARIO</h3>
        <table>
            <tr>
                <th>Nombre</th>
                <td>{{ isset($solicitud->propietario['nombre_completo']) ? $solicitud->propietario['nombre_completo'] : 'N/A' }}
                </td>
                <th>Documento</th>
                <td>{{ isset($solicitud->propietario['numero_documento']) ? $solicitud->propietario['numero_documento'] : 'N/A' }}
                </td>
            </tr>
            <tr>
                <th>Dirección</th>
                <td colspan="3">
                    {{ isset($solicitud->propietario['direccion']) ? $solicitud->propietario['direccion'] : 'N/A' }}
                </td>
            </tr>
            <tr>
                <th>Teléfono</th>
                <td>{{ isset($solicitud->propietario['telefono']) ? $solicitud->propietario['telefono'] : 'N/A' }}</td>
                <th>Email</th>
                <td>{{ isset($solicitud->propietario['email']) ? $solicitud->propietario['email'] : 'N/A' }}</td>
            </tr>
        </table>
    </div>

    @if (count($detalles) > 0)
        <div class="info-container">
            <h3>DETALLES DE LA SOLICITUD</h3>
            <table>
                <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Valor Anterior</th>
                        <th>Valor Nuevo</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->campo }}</td>
                            <td>{{ $detalle->valor_anterior }}</td>
                            <td>{{ $detalle->valor_nuevo }}</td>
                            <td>{{ $detalle->descripcion }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="footer">
        <div class="signature-box">
            Firma del Solicitante
        </div>
        <div class="signature-box">
            Firma del Responsable
        </div>
    </div>
</body>

</html>
