<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora #{{ $bitacora->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 1px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 10px; }
        th, td { border: 1px solid black; text-align: center; padding: 4px; }
        th { background-color: #f2f2f2; }
        .header-table { width: 100%;  font-size: 14px; border: none; }
        .header-table td { border: none; text-align: center; vertical-align: middle; padding: 5px; }
        .header-logo { width: 100px; }
        .header-content h1 { margin: 0; font-size: 16px; }
        .header-content h2 { margin: 5px 0; font-size: 14px; }
        .details { text-align: center; margin-top: 5px; font-size: 16px; }
        .details-table { width: 50%; margin: 0 auto; font-size: 12px; border-collapse: collapse; }
        .details-table th, .details-table td { text-align: left; padding: 4px; border: none; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td rowspan="2" class="header-logo">
                <img src="{{ $logoPath }}" alt="Logo"  width="150%">
            </td>
            <td>
                <h2>PROCEDIMIENTO OPERATIVO ESTANDA <br>Carga y Descarga de Productos<br>
                    <p>Bitácora diaria de monitoreo del transporte SOLUSERLOG CIA. LTDA.</p>
                </h2>
            </td>
        </tr>
        <div class="details">
            <table class="details-table">
                <tr>
                    <th>Chofer</th>
                    <td>{{ $bitacora->guia->conductor->nombre ?? 'N/A' }}</td>
                    <th>Proyecto</th>
                    <td>{{ $bitacora->guia->manifiesto->tipoFlete ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Ayudante</th>
                    <td>{{ $bitacora->guia->ayudante->nombre ?? 'N/A' }}</td>
                    <th>Manifiesto</th>
                    <td>{{ $bitacora->guia->manifiesto->numero_manifiesto ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Placa</th>
                    <td>{{ $bitacora->guia->manifiesto->camion->numero_placa ?? 'N/A' }}</td>
                    <th>Fecha</th>
                    <td>{{ $bitacora->guia->manifiesto->fecha_inicio_traslado ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

    </table>


    <table>
        <thead>
            <tr>
                <th colspan="6">Carga</th>
                <th colspan="8">Descarga</th>
            </tr>
            <tr>
                <th>Origen</th>
                <th>Hora Llegada</th>
                <th>Temperatura y Humedad</th>
                <th>Hora de Carga</th>
                <th>Hora de Salida</th>
                <th>Novedades de Carga</th>
                <th>Destino</th>
                <th>Temperatura y Humedad</th>
                <th>Hora Llegada</th>
                <th>Hora Descarga</th>
                <th>Hora Salida</th>
                <th># Facturas</th>
                <th>Novedades en Destino</th>
                <th>Firma</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bitacora->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->order->direccionRemitente->cliente->razonSocial }}</td>
                    <td>{{ $detalle->hora_origen_llegada ?? 'N/A' }}</td>
                    <td>{{ $detalle->temperatura_origen }} / {{ $detalle->humedad_origen }}</td>
                    <td>{{ $detalle->hora_carga ?? 'N/A' }}</td>
                    <td>{{ $detalle->hora_salida_origen ?? 'N/A' }}</td>
                    <td>{{ $detalle->novedades_carga ?? 'N/A' }}</td>
                    <td>{{ $detalle->order->direccionDestinatario->cliente->razonSocial }}</td>
                    <td>{{ $detalle->temperatura_destino }} / {{ $detalle->humedad_destino }}</td>
                    <td>{{ $detalle->hora_destino_llegada ?? 'N/A' }}</td>
                    <td>{{ $detalle->hora_descarga ?? 'N/A' }}</td>
                    <td>{{ $detalle->hora_salida_destino ?? 'N/A' }}</td>
                    <td>
                        @if ($detalle->order->documents->count() > 0)
                            {{ $detalle->order->documents->pluck('factura')->join(', ') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $detalle->novedades_destino ?? 'N/A' }}</td>
                    <td>{{ $detalle->firma_recepcion ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
