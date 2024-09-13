<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\Order;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener todos los IDs de los pedidos existentes
        $orderIds = Order::pluck('id');

        foreach ($orderIds as $orderId) {
            // Crear 3 o 4 documentos para cada pedido
            Document::factory()
                ->count(rand(3, 4))
                ->create(['order_id' => $orderId]);
        }
    }
}
