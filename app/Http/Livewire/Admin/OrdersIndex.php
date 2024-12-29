<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $search;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $orders = Order::where('estado', '=', 0)
            ->where(function ($query) {
                $query->whereHas('direccionRemitente.cliente', function ($q) {
                    $q->where('razonSocial', 'LIKE', '%' . $this->search . '%');
                })
                    ->orWhereHas('documents', function ($q) {
                        $q->where('factura', 'LIKE', '%' . $this->search . '%');
                    });
            })
            ->latest('id')
            ->paginate();

        return view('livewire.admin.orders-index')->with('orders', $orders);
    }

    public $documents;

    public function showDocuments($orderId)
    {
        $order = Order::find($orderId);
        $this->documents = $order ? $order->documents : collect();
    }
}
