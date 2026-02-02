<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tracking Pedido</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }

        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td { border: 1px solid black; padding: 5px; text-align: left; vertical-align: top; }

        .header-table {
            width: 100%;
            border: 1px solid black;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        .header-table td { border: 1px solid black; text-align: center; padding: 6px; }
        .header-table .logo { width: 20%; text-align: left; vertical-align: middle; }
        .header-table .logo img { max-width: 140px; height: auto; }
        .header-table .title { width: 60%; font-weight: bold; font-size: 16px; text-align: center; vertical-align: middle; }
        .header-table .code { width: 20%; font-size: 12px; text-align: right; vertical-align: top; }

        .subheader {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-top: 8px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        h3 { margin: 16px 0 6px 0; font-size: 13px; }

        /* IMÁGENES - estable en DomPDF */
        .images-table {
            width: 100%;
            border: none;
            margin-top: 10px;
            table-layout: fixed;
            border-collapse: collapse;
        }
        .images-table td {
            border: none;
            text-align: center;
            vertical-align: top;
            padding: 6px;
            width: 33.33%;
        }
        .images-table img {
            width: 100%;
            max-width: 180px;
            height: 140px;
            object-fit: cover;
            border: 1px solid #000;
            padding: 4px;
        }

        .muted { color: #444; font-size: 11px; }
        .box { border: 1px solid #000; padding: 8px; margin-top: 10px; }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>

    <!-- Encabezado -->
    <table class="header-table">
        <tr>
            <td class="logo">
                <img src="{{ $logoPath }}" alt="Logo">
            </td>
            <td class="title">
                PROCEDIMIENTO OPERATIVO ESTÁNDAR<br>
                MONITOREO DE RUTAS Y REPORTE DE NOVEDADES
            </td>
            <td class="code">
                DO-POE-03-F4-V00
            </td>
        </tr>
    </table>

    <!-- Subtítulo -->
    <div class="subheader">
        TRACKING DE TRANSPORTE SOLUSERLOG
    </div>

    <!-- Información principal -->
    <table>
        <tr>
            <th># FACTURA</th>
            <th>FECHA FACTURA</th>
            <th>FECHA ENTREGA</th>
        </tr>
        <tr>
            <td>{{ $order->documents->pluck('factura')->filter()->join(', ') ?: 'N/A' }}</td>
            <td>{{ $order->fechaCreacion ?? 'N/A' }}</td>
            <td>{{ $order->fechaEntrega ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- Información adicional -->
    <table>
        <tr>
            <th>CÓDIGO DEL CLIENTE</th>
            <th>NOMBRE DEL CLIENTE</th>
            <th>DIRECCIÓN</th>
            <th>CIUDAD</th>
        </tr>
        <tr>
            <td>{{ $order->direccionDestinatario->cliente->codigoCliente ?? 'N/A' }}</td>
            <td>{{ $order->direccionDestinatario->cliente->razonSocial ?? 'N/A' }}</td>
            <td>{{ $order->direccionDestinatario->direccion ?? 'N/A' }}</td>
            <td>{{ $order->direccionDestinatario->ciudad ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- Información de descarga -->
    <h3>INFORMACIÓN DE DESCARGA</h3>
    <table>
        <tr>
            <th class="nowrap">HORA DESCARGA</th>
            <th class="nowrap">TEMP. DESCARGA</th>
            <th class="nowrap">HUMEDAD DESCARGA</th>
        </tr>

        @php $hayDescarga = false; @endphp

        @if ($filteredManifiestos->count())
            @foreach ($filteredManifiestos as $manifiesto)
                @foreach ($manifiesto->guias as $guia)
                    @foreach ($guia->bitacora->detalles as $detalle)
                        @php $hayDescarga = true; @endphp
                        <tr>
                            <td>{{ $detalle->hora_descarga ?? 'N/A' }}</td>
                            <td>{{ $detalle->temperatura_destino ?? 'N/A' }}</td>
                            <td>{{ $detalle->humedad_destino ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @endif

        @if(!$hayDescarga)
            <tr><td colspan="3">Sin datos</td></tr>
        @endif
    </table>

    @php
    $personaRecibe = null;

    if ($filteredManifiestos->count()) {
        foreach ($filteredManifiestos as $manifiesto) {
            foreach ($manifiesto->guias as $guia) {
                foreach ($guia->bitacora->detalles as $detalle) {
                    $p = trim((string)($detalle->persona ?? ''));
                    if ($p !== '') {
                        $personaRecibe = $p;
                        break 3; // sale de 3 foreach
                    }
                }
            }
        }
    }
@endphp



    <h3>NOVEDADES (REGISTRO)</h3>

    <div class="box">
        <strong>PERSONA QUE RECIBE:</strong> {{ $personaRecibe ?: 'N/A' }}
    </div>

    <table>
        <tr>
            <th style="width: 18%;">TIPO</th>
            <th>NOVEDAD ESCRITA</th>
        </tr>

        @php $hayNovedad = false; @endphp

        @if ($filteredManifiestos->count())
            @foreach ($filteredManifiestos as $manifiesto)
                @foreach ($manifiesto->guias as $guia)
                    @foreach ($guia->bitacora->detalles as $detalle)

                        @php
                            $nc = trim((string)($detalle->novedades_carga ?? ''));
                            $nd = trim((string)($detalle->novedades_destino ?? ''));
                        @endphp

                        @if($nc !== '')
                            @php $hayNovedad = true; @endphp
                            <tr>
                                <td><strong>CARGA</strong></td>
                                <td>{{ $nc }}</td>
                            </tr>
                        @endif

                        @if($nd !== '')
                            @php $hayNovedad = true; @endphp
                            <tr>
                                <td><strong>DESTINO</strong></td>
                                <td>{{ $nd }}</td>
                            </tr>
                        @endif

                    @endforeach
                @endforeach
            @endforeach
        @endif

        @if(!$hayNovedad)
            <tr>
                <td colspan="2">Sin novedades registradas.</td>
            </tr>
        @endif
    </table>


    <!-- Evidencia fotográfica -->
    <h3>EVIDENCIA FOTOGRÁFICA</h3>

    @php
        $imgs = collect();

        if ($filteredManifiestos->count()) {
            foreach ($filteredManifiestos as $manifiesto) {
                foreach ($manifiesto->guias as $guia) {
                    foreach ($guia->bitacora->detalles as $detalle) {
                        foreach ($detalle->images as $image) {
                            $imgs->push($image);
                        }
                    }
                }
            }
        }
    @endphp

    @if($imgs->count())
        <table class="images-table">
            @foreach($imgs->chunk(3) as $fila)
                <tr>
                    @foreach($fila as $image)
                        <td>
                            <img src="{{ public_path('storage/' . $image->image_path) }}" alt="Evidencia">
                        </td>
                    @endforeach
                    @for($i = $fila->count(); $i < 3; $i++)
                        <td></td>
                    @endfor
                </tr>
            @endforeach
        </table>
    @else
        <p>Sin imágenes asociadas.</p>
    @endif

</body>
</html>
