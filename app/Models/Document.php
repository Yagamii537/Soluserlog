<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Evento para actualizar totales en el pedido asociado
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($document) {
            $document->updateOrderTotals();
        });

        static::deleted(function ($document) {
            $document->updateOrderTotals();
        });
    }

    public function updateOrderTotals()
    {
        if ($this->order_id) {
            $order = $this->order;

            $totals = $order->documents()
                ->selectRaw('SUM(cantidad_bultos) as total_bultos, SUM(cantidad_kg) as total_kg')
                ->first();

            $order->update([
                'totaBultos' => $totals->total_bultos ?? 0,
                'totalKgr' => $totals->total_kg ?? 0,
            ]);
        }
    }
}
