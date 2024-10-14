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
            ->where('remitente', 'LIKE', '%' . $this->search . '%')
            ->latest('id')
            ->paginate();
        return view('livewire.admin.orders-index')->with('orders', $orders);;
    }
}
