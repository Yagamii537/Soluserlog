<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Order;
use App\Models\Cliente;
use App\Models\Document;
use Illuminate\Database\Seeder;
use Database\Seeders\DocumentSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class

        ]);

        User::create([
            'name' => 'Mauricio Peñafiel',
            'email' => 'mauriciosistemas18@gmail.com',
            'password' => bcrypt('dragonruto18')
        ])->assignRole('admin');

        User::create([
            'name' => 'Fernanda Hidalgo',
            'email' => 'soluserlog.cia.ltda@gmail.com',
            'password' => bcrypt('soluserlogAdmin')
        ])->assignRole('admin');
        User::create([
            'name' => 'Jean Pierre villareal',
            'email' => 'jpierre@correo.com',
            'password' => bcrypt('soluserlogAuxiliar')
        ])->assignRole('admin');

        User::create([
            'name' => 'David Romero',
            'email' => 'david.romero@bbraun.com',
            'password' => bcrypt('davidBraun')
        ])->assignRole('pedidos');

        User::create([
            'name' => 'Daniel Atarihuana',
            'email' => 'daniel.atarihuana@bbraun.com',
            'password' => bcrypt('danielBraun')
        ])->assignRole('pedidos');

        User::create([
            'name' => 'Gabriela Carlosama',
            'email' => 'gabriela.carlosama@bbraun.com',
            'password' => bcrypt('gabrielaBraun')
        ])->assignRole('pedidos');


        // Crear clientes con direcciones asociadas
        $clientes = Cliente::factory()
            ->count(20)
            ->hasAddresses(2) // Cada cliente tendrá 2 direcciones
            ->create();

        // // Crear órdenes con direcciones de remitente y destinatario
        // foreach ($clientes as $cliente) {
        //     // Obtener dos direcciones aleatorias para el remitente y destinatario
        //     $direcciones = $cliente->addresses()->inRandomOrder()->take(2)->get();

        //     if ($direcciones->count() >= 2) {
        //         Order::factory()->count(1)->create([
        //             'remitente_direccion_id' => $direcciones[0]->id,
        //             'destinatario_direccion_id' => $direcciones[1]->id,
        //         ]);
        //     }
        // }

        // $this->call([
        //     DocumentSeeder::class
        // ]);
    }
}
