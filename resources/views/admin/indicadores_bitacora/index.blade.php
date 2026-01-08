@extends('adminlte::page')

@section('title', 'Indicadores')

@section('content_header')
  <h1>Indicadores – Semana {{ $rangeLabel }}</h1>
@stop

@section('content')
<form method="GET" action="{{ route('admin.indicadores_bitacora.index') }}" class="mb-3">
  <div class="row g-2">
    <div class="col-md-3">
      <label class="form-label">Fecha inicio</label>
      <input type="date" name="start_date" class="form-control" value="{{ $startDate ?? '' }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">Fecha fin</label>
      <input type="date" name="end_date" class="form-control" value="{{ $endDate ?? '' }}">
    </div>
    <div class="col-md-3 d-flex align-items-end">
      <button class="btn btn-primary w-100" type="submit">
        <i class="fas fa-filter"></i> Filtrar
      </button>
    </div>
    <div class="col-md-3 d-flex align-items-end">
      <a href="{{ route('admin.indicadores_bitacora.index') }}" class="btn btn-outline-secondary w-100">
        <i class="fas fa-redo"></i> Limpiar
      </a>
    </div>
  </div>
</form>

  <div class="card">
    <div class="card-header">
      <i class="fas fa-chart-bar"></i> Bitácoras con novedad por día (L–D)
    </div>
    <div class="card-body">
      <canvas id="barConNovedad" style="max-height: 320px;"></canvas>
      <small class="text-muted d-block mt-2">
        Click en una barra para ver los pedidos con novedad de ese día.
      </small>
    </div>
  </div>

  <div class="card mt-3">
  <div class="card-header">
    <i class="fas fa-chart-pie"></i> Distribución de novedades (semana/rango)
  </div>
  <div class="card-body">
    <canvas id="pieNovedades" style="max-height: 320px;"></canvas>
    <small class="text-muted d-block mt-2">
      El pastel cuenta la cantidad de checks por novedad (ej: DESTINO: ... = 4).
    </small>
  </div>
</div>


  {{-- Aquí se insertará la tabla del día seleccionado --}}
  <div id="tabla-dia" class="mt-3"></div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const labels = @json($labels); // ['L','M','X','J','V','S','D']
  const dates  = @json($dates);  // ['YYYY-MM-DD', ...]
  const data   = @json($data);

  const ctx = document.getElementById('barConNovedad');
  const chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{ label: 'Con novedad', data: data }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            title: (items) => dates[items[0].dataIndex]
          }
        }
      },
      scales: { y: { beginAtZero: true, precision: 0 } },
      onClick: async (evt, elements) => {
        if (!elements.length) return;
        const idx = elements[0].index;
        const fecha = dates[idx];
        const cont = document.getElementById('tabla-dia');
        cont.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>';

        try {
          const resp = await fetch(`{{ route('admin.indicadores_bitacora.dia') }}?fecha=${fecha}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          });
          const html = await resp.text();
          cont.innerHTML = html;
          cont.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch (e) {
          cont.innerHTML = '<div class="alert alert-danger">No se pudo cargar la tabla.</div>';
        }
      }
    }
  });


  const pieLabels = @json($pieLabels ?? []);
const pieData   = @json($pieData ?? []);

const pieEl = document.getElementById('pieNovedades');
if (pieEl) {
  if (!pieLabels.length) {
    pieEl.parentElement.insertAdjacentHTML(
      'beforeend',
      '<div class="alert alert-info mt-2 mb-0">No hay novedades registradas en este rango.</div>'
    );
  } else {
    new Chart(pieEl, {
    type: 'pie',
    data: {
        labels: pieLabels,
        datasets: [{ data: pieData }]
    },
    options: {
        responsive: true,
        plugins: {
        legend: { position: 'bottom' },
        tooltip: {
            callbacks: {
            label: (ctx) => {
                const total = ctx.dataset.data.reduce((a,b)=>a+b,0);
                const val = ctx.parsed;
                const pct = total ? ((val / total) * 100).toFixed(1) : 0;
                return ` ${ctx.label}: ${val} (${pct}%)`;
            }
            }
        }
        },
        onClick: async (evt, elements) => {
        if (!elements.length) return;

        const idx = elements[0].index;
        const label = pieLabels[idx]; // "DESTINO: DOCUMENTACION ..."

        // label viene como "DESTINO: XXXX" o "CARGA: XXXX"
        const parts = label.split(':');
        const tipo = (parts[0] || '').trim().toLowerCase(); // destino | carga
        const opcion = parts.slice(1).join(':').trim();     // texto completo

        const cont = document.getElementById('tabla-dia');
        cont.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>';

        // Mandamos el mismo rango del filtro (si existe)
        const start = document.querySelector('input[name="start_date"]')?.value || '';
        const end   = document.querySelector('input[name="end_date"]')?.value || '';

        try {
            const url = `{{ route('admin.indicadores_bitacora.novedad') }}?tipo=${encodeURIComponent(tipo)}&opcion=${encodeURIComponent(opcion)}&start_date=${encodeURIComponent(start)}&end_date=${encodeURIComponent(end)}`;
            const resp = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await resp.text();
            cont.innerHTML = html;
            cont.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch (e) {
            cont.innerHTML = '<div class="alert alert-danger">No se pudo cargar la tabla.</div>';
        }
        }
    }
    });

  }
}

</script>
@stop
