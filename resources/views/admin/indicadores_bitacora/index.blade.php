@extends('adminlte::page')

@section('title', 'Indicadores de Bitácora')

@section('content')

<div class="container">
    <h2 class="mb-4">📊 Indicadores por Checklist (mes actual)</h2>
    <form method="GET" action="{{ route('admin.indicadores_bitacora.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="start_date">Fecha Inicio:</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date">Fecha Fin:</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('admin.indicadores_bitacora.index') }}" class="btn btn-secondary ml-2">Limpiar</a>
            </div>
        </div>
    </form>

    <div class="row">
        @foreach ($estadisticas as $opcion => $registros)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center py-2" style="font-size: 0.85rem;">
                        {{ strtoupper($opcion) }}
                    </div>
                    <div class="card-body">
                        <canvas id="chart-{{ Str::slug($opcion, '-') }}" width="300" height="200"></canvas>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('js')
@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @foreach ($estadisticas as $opcion => $registros)
        const registros_{{ Str::slug($opcion, '_') }} = {!! json_encode($registros) !!};
        if (registros_{{ Str::slug($opcion, '_') }}.length > 0) {
            const ctx = document.getElementById('chart-{{ Str::slug($opcion, '-') }}').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: registros_{{ Str::slug($opcion, '_') }}.map(r => r.fecha),
                    datasets: [{
                        label: 'Cantidad',
                        data: registros_{{ Str::slug($opcion, '_') }}.map(r => r.total),
                        backgroundColor: '#3498db',
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0 // evita decimales
                            }
                        }
                    },
                    onClick: (e, elements) => {
                        if (elements.length > 0) {
                            const chart = elements[0].element.$context.chart;
                            const index = elements[0].index;
                            const fecha = chart.data.labels[index];
                            const url = `{{ url('/admin/indicadores-bitacora') }}/{{ $tipos[$opcion] }}/{{ urlencode($opcion) }}?fecha=${fecha}`;
                            window.location.href = url;
                        }
                    }
                }
            });
        }
    @endforeach
</script>
@endsection

@endsection
