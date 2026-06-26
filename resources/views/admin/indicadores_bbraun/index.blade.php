@extends('adminlte::page')

@section('title', 'Indicadores B. Braun')

@section('css')
<style>
    .small-box { min-height: 85px; margin-bottom: 12px; }
    .small-box .inner { padding: 10px; }
    .small-box h3 { font-size: 22px; margin-bottom: 4px; font-weight: bold; }
    .small-box p { font-size: 12px; margin-bottom: 0; }
    .small-box .icon { font-size: 50px; top: 8px; }
    .card-header { font-weight: bold; }
    .chart-box { min-height: 350px; }
    .chart-box .card-body { height: 300px; }
    .table td, .table th { vertical-align: middle; font-size: 12px; }
</style>
@stop

@section('content_header')
    <h1><i class="fas fa-chart-line"></i> Indicadores B. Braun</h1>
@stop

@section('content')
<form id="formPdf" method="POST" action="{{ route('admin.indicadores_bbraun.pdf') }}" target="_blank">
    @csrf

    <input type="hidden" name="start_date" value="{{ $startDate }}">
    <input type="hidden" name="end_date" value="{{ $endDate }}">
    <input type="hidden" name="novedad_destino" value="{{ $novedadFiltro }}">
    <input type="hidden" name="tipo_flete" value="{{ $tipoFleteFiltro }}">
    <input type="hidden" name="tonelaje" value="{{ $tonelajeFiltro }}">

    <input type="hidden" name="chartNovedades" id="imgChartNovedades">
    <input type="hidden" name="chartClientes" id="imgChartClientes">
    <input type="hidden" name="chartDestinos" id="imgChartDestinos">
    <input type="hidden" name="chartConductores" id="imgChartConductores">
    <input type="hidden" name="chartCamiones" id="imgChartCamiones">
    <input type="hidden" name="chartFlete" id="imgChartFlete">
    <input type="hidden" name="chartTonelaje" id="imgChartTonelaje">
    <input type="hidden" name="chartBultos" id="imgChartBultos">
    <input type="hidden" name="chartKg" id="imgChartKg">
    <input type="hidden" name="chartDias" id="imgChartDias">
    <input type="hidden" name="chartHoras" id="imgChartHoras">

    <button type="button" onclick="generarPDF()" class="btn btn-danger mb-3">
        <i class="fas fa-file-pdf"></i> Imprimir PDF
    </button>
</form>

<form method="GET" action="{{ route('admin.indicadores_bbraun.index') }}" class="mb-3">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <label>Fecha inicio</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>

                <div class="col-md-2">
                    <label>Fecha fin</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>

                <div class="col-md-3">
                    <label>Novedades destino</label>
                    <select name="novedad_destino" class="form-control">
                        <option value="">Todas</option>
                        @foreach($novedadesFiltro as $nov)
                            <option value="{{ $nov }}" {{ $novedadFiltro == $nov ? 'selected' : '' }}>
                                {{ $nov === 'ENTREGA SIN NOVEDAD' ? 'S/N - ENTREGA SIN NOVEDAD' : $nov }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label>Flete</label>
                    <select name="tipo_flete" class="form-control">
                        <option value="">Todos</option>
                        @foreach($fletesFiltro as $flete)
                            <option value="{{ $flete }}" {{ $tipoFleteFiltro == $flete ? 'selected' : '' }}>
                                {{ $flete }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1">
                    <label>Tonelaje</label>
                    <select name="tonelaje" class="form-control">
                        <option value="">Todos</option>
                        <option value="1" {{ $tonelajeFiltro == '1' ? 'selected' : '' }}>1T</option>
                        <option value="3" {{ $tonelajeFiltro == '3' ? 'selected' : '' }}>3T</option>
                        <option value="5" {{ $tonelajeFiltro == '5' ? 'selected' : '' }}>5T</option>
                        <option value="10" {{ $tonelajeFiltro == '10' ? 'selected' : '' }}>10T</option>
                    </select>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <a href="{{ route('admin.indicadores_bbraun.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-2">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalSolicitudes }}</h3>
                <p>Total solicitudes</p>
            </div>
            <div class="icon"><i class="fas fa-clipboard-list"></i></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $totalFacturas }}</h3>
                <p>Total facturas</p>
            </div>
            <div class="icon"><i class="fas fa-file-invoice"></i></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $facturasEntregadas }}</h3>
                <p>Facturas entregadas</p>
            </div>
            <div class="icon"><i class="fas fa-truck"></i></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalBultos }}</h3>
                <p>Total bultos</p>
            </div>
            <div class="icon"><i class="fas fa-boxes"></i></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ number_format($totalKg, 2) }}</h3>
                <p>Total KG</p>
            </div>
            <div class="icon"><i class="fas fa-weight"></i></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $clientesUnicos }}</h3>
                <p>Clientes únicos</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-2">
        <div class="small-box bg-dark">
            <div class="inner">
                <h3>{{ $ciudadesUnicas }}</h3>
                <p>Ciudades</p>
            </div>
            <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card chart-box">
            <div class="card-header bg-primary text-white">Novedades destino</div>
            <div class="card-body"><canvas id="chartNovedades"></canvas></div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card chart-box">
            <div class="card-header bg-success text-white">Top clientes</div>
            <div class="card-body"><canvas id="chartClientes"></canvas></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card chart-box">
            <div class="card-header bg-info text-white">Destinos / ciudades</div>
            <div class="card-body"><canvas id="chartDestinos"></canvas></div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card chart-box">
            <div class="card-header bg-secondary text-white">Conductores</div>
            <div class="card-body"><canvas id="chartConductores"></canvas></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card chart-box">
            <div class="card-header bg-dark text-white">Camiones</div>
            <div class="card-body"><canvas id="chartCamiones"></canvas></div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card chart-box">
            <div class="card-header bg-warning">Flete fijo / adicional</div>
            <div class="card-body"><canvas id="chartFlete"></canvas></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card chart-box">
            <div class="card-header bg-danger text-white">Tonelaje</div>
            <div class="card-body"><canvas id="chartTonelaje"></canvas></div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card chart-box">
            <div class="card-header bg-warning">Bultos por rango</div>
            <div class="card-body"><canvas id="chartBultos"></canvas></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card chart-box">
            <div class="card-header bg-danger text-white">KG por rango</div>
            <div class="card-body"><canvas id="chartKg"></canvas></div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card chart-box">
            <div class="card-header bg-primary text-white">Entregas por día</div>
            <div class="card-body"><canvas id="chartDias"></canvas></div>
        </div>
    </div>
</div>

<div class="card chart-box">
    <div class="card-header bg-success text-white">Entregas por hora de descarga</div>
    <div class="card-body"><canvas id="chartHoras"></canvas></div>
</div>

<div class="card">
    <div class="card-header bg-dark text-white">Detalle de facturas / entregas</div>

    <div class="card-body table-responsive">
        <table class="table table-striped table-sm">
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
                            ->map(fn($v) => $v === 'ENTREGA SIN NOVEDAD' ? 'S/N' : $v)
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
                        <td>{{ $camion->numero_placa ?? 'N/A' }} {{ $camion->marca ?? '' }}</td>
                        <td>{{ $novedadesDestino ?: 'N/A' }}</td>
                        <td>{{ $detalle->persona ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center">No hay datos en el rango seleccionado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $tabla->links() }}
        </div>
    </div>
</div>

@stop
@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
    Chart.register(ChartDataLabels);

    function capturarGrafico(canvasId, inputId) {
        const canvas = document.getElementById(canvasId);
        const input = document.getElementById(inputId);

        if (canvas && input) {
            input.value = canvas.toDataURL('image/png');
        }
    }

    function generarPDF() {
        capturarGrafico('chartNovedades', 'imgChartNovedades');
        capturarGrafico('chartClientes', 'imgChartClientes');
        capturarGrafico('chartDestinos', 'imgChartDestinos');
        capturarGrafico('chartConductores', 'imgChartConductores');
        capturarGrafico('chartCamiones', 'imgChartCamiones');
        capturarGrafico('chartFlete', 'imgChartFlete');
        capturarGrafico('chartTonelaje', 'imgChartTonelaje');
        capturarGrafico('chartBultos', 'imgChartBultos');
        capturarGrafico('chartKg', 'imgChartKg');
        capturarGrafico('chartDias', 'imgChartDias');
        capturarGrafico('chartHoras', 'imgChartHoras');

        document.getElementById('formPdf').submit();
    }

    function chartBar(id, labels, data, label = 'Total', horizontal = false) {
        const el = document.getElementById(id);
        if (!el) return;

        new Chart(el, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: horizontal ? 'y' : 'x',
                plugins: {
                    legend: { display: false },
                    datalabels: {
                        anchor: 'end',
                        align: horizontal ? 'right' : 'top',
                        color: '#000',
                        font: {
                            weight: 'bold',
                            size: 10
                        },
                        formatter: function(value) {
                            return value > 0 ? value : '';
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    }

    function chartPie(id, labels, data) {
        const el = document.getElementById(id);
        if (!el) return;

        new Chart(el, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    datalabels: {
                        color: '#000',
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        formatter: function(value, context) {
                            return value > 0 ? value : '';
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => Number(a) + Number(b), 0);
                                const value = Number(context.parsed);
                                const percent = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${context.label}: ${value} (${percent}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    chartPie(
        'chartNovedades',
        @json($novedades->pluck('novedad')),
        @json($novedades->pluck('total'))
    );

    chartBar(
        'chartClientes',
        @json($clientes->pluck('cliente')),
        @json($clientes->pluck('total')),
        'Facturas',
        true
    );

    chartBar(
        'chartDestinos',
        @json($destinos->pluck('destino')),
        @json($destinos->pluck('total')),
        'Entregas',
        true
    );

    chartBar(
        'chartConductores',
        @json($conductores->pluck('conductor')),
        @json($conductores->pluck('total')),
        'Entregas',
        true
    );

    chartBar(
        'chartCamiones',
        @json($camiones->pluck('camion')),
        @json($camiones->pluck('total')),
        'Entregas',
        true
    );

    chartPie(
        'chartFlete',
        @json($fletesGrafico->pluck('flete')),
        @json($fletesGrafico->pluck('total'))
    );

    chartPie(
        'chartTonelaje',
        @json($tonelajesGrafico->pluck('tonelaje')),
        @json($tonelajesGrafico->pluck('total'))
    );

    chartPie(
        'chartBultos',
        @json($bultosRangos->pluck('rango')),
        @json($bultosRangos->pluck('total'))
    );

    chartPie(
        'chartKg',
        @json($kgRangos->pluck('rango')),
        @json($kgRangos->pluck('total'))
    );

    chartBar(
        'chartDias',
        @json($entregasDias->pluck('dia')),
        @json($entregasDias->pluck('total')),
        'Entregas'
    );

    chartBar(
        'chartHoras',
        @json($entregasPorHora->pluck('hora')),
        @json($entregasPorHora->pluck('total')),
        'Entregas'
    );
</script>
@stop
