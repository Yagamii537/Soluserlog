<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>
      <i class="fas fa-list"></i> Pedidos con novedad – <strong>{{ $fecha }}</strong>
    </span>
    <span class="badge bg-primary">{{ $porPedido->count() }} pedido(s)</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-sm table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th style="width: 90px;">Pedido ID</th>
            <th>Cliente / Destinatario</th>
            <th>Ciudad</th>
            <th>Novedades</th>
            <th style="width: 140px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
        @forelse($porPedido as $row)
          @php
            $order = $row['order'];

            // Cliente ORIGEN (remitente)
            $cliOrigen = optional(optional($order->direccionRemitente)->cliente);

            // Cliente DESTINO (destinatario)
            $cliDestino = optional(optional($order->direccionDestinatario)->cliente);

            // Ciudad sale de la dirección destino
            $dest = optional($order->direccionDestinatario);
          @endphp

          <tr>
            <td>{{ $order->id }}</td>
            <td>
            <div>
                <strong>{{ $cliOrigen->razonSocial ?? $cliOrigen->nombre ?? '—' }}</strong>
            </div>
            <div class="text-muted small">
                {{ $cliDestino->razonSocial ?? $cliDestino->nombre ?? '—' }}
            </div>
            </td>



            <td>{{ $dest->ciudad ?? '—' }}</td>
            <td>
              @foreach($row['novedades'] as $nov)
                <span class="badge bg-warning text-dark me-1 mb-1">{{ $nov }}</span>
              @endforeach
            </td>
            <td>
              @if(!empty($row['bitacora_ids']))
    @foreach($row['bitacora_ids'] as $bId)
      <a href="{{ route('admin.detalle_bitacoras.edit', ['bitacora' => $bId, 'order' => $row['order']->id]) }}"
         class="btn btn-xs btn-outline-primary mb-1">
        <i class="fas fa-clipboard-check"></i> Ver bitácora
      </a>
    @endforeach
  @else
    <button class="btn btn-xs btn-outline-secondary mb-1" disabled title="Sin bitácora vinculada">
      <i class="fas fa-clipboard-check"></i> Ver bitácora
    </button>
  @endif
              @php
                $factura = optional($order->documents->first())->factura;
                @endphp

                @if($factura)
                <a href="{{ route('admin.tracking.lookupFactura', ['factura' => $factura]) }}"
                    class="btn btn-xs btn-outline-secondary">
                    <i class="fas fa-file-invoice"></i> Tracking
                </a>
                @else
                <button class="btn btn-xs btn-outline-secondary" disabled title="Pedido sin factura">
                    <i class="fas fa-file-invoice"></i> Tracking
                </button>
                @endif

            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center p-4">Sin resultados para la fecha seleccionada.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
