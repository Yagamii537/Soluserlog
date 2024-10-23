@extends('adminlte::page')

@section('title', 'Dashboard')

{{-- icono de carga --}}
@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">Loading</h4>
@stop

@section('adminlte_css')
    @vite(['resources/js/app.js'])
@stop

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

<div class="row">
    {{-- Tarjeta para pedidos confirmados --}}
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $pedidosConfirmados }}</h3>
                <p>Pedidos Confirmados</p>
            </div>
            <div class="icon">
                <i class="fas fa-check"></i>
            </div>
        </div>
    </div>

    {{-- Tarjeta para pedidos no confirmados --}}
    <div class="col-lg-4 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $pedidosNoConfirmados }}</h3>
                <p>Pedidos No Confirmados</p>
            </div>
            <div class="icon">
                <i class="fas fa-times"></i>
            </div>
        </div>
    </div>

    {{-- Tarjeta para manifiestos en proceso --}}
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $manifiestosEnProceso }}</h3>
                <p>Manifiestos en Proceso</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
        </div>
    </div>
</div>

{{-- Gráfico de Pedidos por Día en la Semana --}}
<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pedidos por Día (Última Semana)</h3>
            </div>
            <div class="card-body">
                <canvas id="pedidosChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Gráfico de Pastel: Pedidos Confirmados vs No Confirmados --}}
<div class="row mt-5">
    <div class="col-md-6 mx-auto"> {{-- Ajustar el tamaño de la columna --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pedidos Confirmados vs No Confirmados</h3>
            </div>
            <div class="card-body">
                <canvas id="pieChart" width="300" height="300"></canvas> {{-- Ajustar el tamaño del canvas --}}
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
    {{-- Opcional: puedes añadir tus estilos personalizados aquí --}}
@stop

@section('js')
    <!-- Incluir Chart.js desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Gráfico de barras: Pedidos por Día --}}
    <script>
        var ctx = document.getElementById('pedidosChart').getContext('2d');
        var pedidosChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($fechas) !!}, // Fechas de los pedidos
                datasets: [{
                    label: 'Pedidos por Día',
                    data: {!! json_encode($cantidades) !!}, // Cantidades de pedidos por día
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });
    </script>

    {{-- Gráfico de Pastel: Pedidos Confirmados vs No Confirmados --}}
    <script>
        var pieCtx = document.getElementById('pieChart').getContext('2d');
        var pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Pedidos Confirmados', 'Pedidos No Confirmados'],
                datasets: [{
                    data: [{{ $pedidosConfirmados }}, {{ $pedidosNoConfirmados }}], // Datos para el gráfico
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)', // Color para pedidos confirmados
                        'rgba(255, 99, 132, 0.6)'  // Color para pedidos no confirmados
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)', // Borde para pedidos confirmados
                        'rgba(255, 99, 132, 1)'  // Borde para pedidos no confirmados
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Permitir que el gráfico se ajuste al tamaño del canvas
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    </script>
@stop
