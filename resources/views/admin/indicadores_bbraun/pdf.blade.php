<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Indicadores B. Braun</title>

    <style>
        @page {
            margin: 18px 20px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #111;
        }

        h2 {
            text-align: center;
            margin: 0 0 8px 0;
            font-size: 18px;
        }

        h3 {
            margin: 10px 0 6px 0;
            font-size: 13px;
        }

        .filtros {
            margin-bottom: 10px;
            border: 1px solid #333;
            padding: 6px;
            font-size: 10px;
        }

        .row {
            width: 100%;
            clear: both;
            page-break-inside: avoid;
        }

        .chart {
            width: 48%;
            float: left;
            margin: 1%;
            border: 1px solid #bbb;
            padding: 6px;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        .chart-full {
            width: 98%;
            margin: 1%;
            border: 1px solid #bbb;
            padding: 6px;
            box-sizing: border-box;
            page-break-inside: avoid;
            clear: both;
        }

        .titulo {
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
            font-size: 11px;
        }

        img {
            width: 100%;
            height: auto;
        }

        .page-break {
            page-break-after: always;
            clear: both;
        }

        .clear {
            clear: both;
        }

        .detalle-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5px;
        }

        .detalle-table th,
        .detalle-table td {
            border: 1px solid #333;
            padding: 3px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
        }

        .detalle-table th {
            background: #eaeaea;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <h2>INDICADORES B. BRAUN</h2>

    <div class="filtros">
        <strong>Rango:</strong> {{ $startDate }} al {{ $endDate }} <br>
        <strong>Novedad:</strong> {{ $novedadFiltro ?: 'Todas' }} |
        <strong>Flete:</strong> {{ $tipoFleteFiltro ?: 'Todos' }} |
        <strong>Tonelaje:</strong>
        @if($tonelajeFiltro)
            {{ $tonelajeFiltro }}T
        @else
            Todos
        @endif
    </div>

    {{-- PÁGINA 1 --}}
    <div class="row">
        <div class="chart">
            <div class="titulo">Novedades destino</div>
            @if(!empty($chartNovedades))
                <img src="{{ $chartNovedades }}">
            @else
                <p class="text-center">Sin gráfico</p>
            @endif
        </div>

        <div class="chart">
            <div class="titulo">Top clientes</div>
            @if(!empty($chartClientes))
                <img src="{{ $chartClientes }}">
            @else
                <p class="text-center">Sin gráfico</p>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="chart">
            <div class="titulo">Destinos / ciudades</div>
            @if(!empty($chartDestinos))
                <img src="{{ $chartDestinos }}">
            @else
                <p class="text-center">Sin gráfico</p>
            @endif
        </div>

        <div class="chart">
            <div class="titulo">Conductores</div>
            @if(!empty($chartConductores))
                <img src="{{ $chartConductores }}">
            @else
                <p class="text-center">Sin gráfico</p>
            @endif
        </div>
    </div>

    <div class="clear"></div>
    <div class="page-break"></div>

    {{-- PÁGINA 2 --}}
    <div class="row">
        <div class="chart">
            <div class="titulo">Camiones</div>
            @if(!empty($chartCamiones))
                <img src="{{ $chartCamiones }}">
            @else
                <p class="text-center">Sin gráfico</p>
            @endif
        </div>

        <div class="chart">
            <div class="titulo">Flete fijo / adicional</div>
            @if(!empty($chartFlete))
                <img src="{{ $chartFlete }}">
            @else
                <p class="text-center">Sin gráfico</p>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="chart">
            <div class="titulo">Tonelaje</div>
            @if(!empty($chartTonelaje))
                <img src="{{ $chartTonelaje }}">
            @else
                <p class="text-center">Sin gráfico</p>
            @endif
        </div>

        <div class="chart">
            <div class="titulo">Bultos por rango</div>
            @if(!empty($chartBultos))
                <img src="{{ $chartBultos }}">
            @else
                <p class="text-center">Sin gráfico</p>
            @endif
        </div>
    </div>

    <div class="clear"></div>
    <div class="page-break"></div>

    {{-- PÁGINA 3 --}}
    <div class="row">
        <div class="chart">
            <div class="titulo">KG por rango</div>
            @if(!empty($chartKg))
                <img src="{{ $chartKg }}">
            @else
                <p class="text-center">Sin gráfico</p>
            @endif
        </div>

        <div class="chart">
            <div class="titulo">Entregas por día</div>
            @if(!empty($chartDias))
                <img src="{{ $chartDias }}">
            @else
                <p class="text-center">Sin gráfico</p>
            @endif
        </div>
    </div>

    <div class="chart-full">
        <div class="titulo">Entregas por hora de descarga</div>
        @if(!empty($chartHoras))
            <img src="{{ $chartHoras }}">
        @else
            <p class="text-center">Sin gráfico</p>
        @endif
    </div>

    <div class="clear"></div>
    <div class="page-break"></div>

    {{-- TABLA DETALLE --}}
    <h3 class="text-center">DETALLE DE FACTURAS / ENTREGAS</h3>

    <table class="detalle-table">
        <thead>
            <tr>
                <th>Factura</th>
                <th>Cliente</th>
                <th>Destino</th>
                <th>Fecha entrega</th>
                <th>Bultos</th>
                <th>KG</th>
                <th>Flete</th>
                <th>Tonelaje</th>
                <th>Conductor</th>
                <th>Camión</th>
                <th>Novedades destino</th>
                <th>Persona recibe</th>
            </tr>
        </thead>

        <tbody>
            @forelse($tabla as $order)
                @php
                    $detalle = optional(
                        $order->manifiestos
                            ->flatMap->guias
                            ->pluck('bitacora')
                            ->filter()
                            ->flatMap->detalles
                            ->where('order_id', $order->id)
                            ->first()
                    );

                    $manifiesto = $order->manifiestos->first();
                    $conductor = optional(optional($manifiesto)->conductor);
                    $camion = optional(optional($manifiesto)->camion);
                    $capacidad = $camion->capacidad_carga ?? null;

                    if (in_array($capacidad, [1, '1', 1000, '1000'])) {
                        $tonelajeTexto = '1T';
                    } elseif (in_array($capacidad, [3, '3', 3000, '3000'])) {
                        $tonelajeTexto = '3T';
                    } elseif (in_array($capacidad, [5, '5', 5000, '5000'])) {
                        $tonelajeTexto = '5T';
                    } elseif (in_array($capacidad, [10, '10', 10000, '10000'])) {
                        $tonelajeTexto = '10T';
                    } else {
                        $tonelajeTexto = 'N/A';
                    }

                    $checksDestino = $detalle->checks ?? collect();

                    $novedadesDestino = $checksDestino
                        ->where('tipo', 'destino')
                        ->pluck('opcion')
                        ->map(function ($v) {
                            return $v === 'ENTREGA SIN NOVEDAD' ? 'S/N' : $v;
                        })
                        ->join(', ');
                @endphp

                <tr>
                    <td>{{ $order->documents->pluck('factura')->filter()->join(', ') ?: 'N/A' }}</td>
                    <td>{{ $order->direccionDestinatario->cliente->razonSocial ?? 'N/A' }}</td>
                    <td>{{ $order->direccionDestinatario->ciudad ?? 'N/A' }}</td>
                    <td>{{ $order->fechaEntrega ?? 'N/A' }}</td>
                    <td>{{ $order->documents->sum('cantidad_bultos') }}</td>
                    <td>{{ number_format($order->documents->sum('cantidad_kg'), 2) }}</td>
                    <td>{{ $manifiesto->tipoFlete ?? 'N/A' }}</td>
                    <td>{{ $tonelajeTexto }}</td>
                    <td>{{ $conductor->nombre ?? 'N/A' }}</td>
                    <td>
                        {{ $camion->numero_placa ?? 'N/A' }}
                        @if($camion && ($camion->marca ?? null))
                            - {{ $camion->marca }}
                        @endif
                    </td>
                    <td>{{ $novedadesDestino ?: 'N/A' }}</td>
                    <td>{{ $detalle->persona ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">
                        No hay datos en el rango seleccionado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
