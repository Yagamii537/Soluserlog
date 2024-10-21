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



        Cliente::factory(20)->create();

        Order::factory()->count(30)->create();

        $this->call([
            DocumentSeeder::class
        ]);

        $this->call([
            RoleSeeder::class,
            ConductorSeeder::class,
            CamionSeeder::class
        ]);

        User::create([
            'name' => 'Mauricio Peñafiel',
            'email' => 'admin@correo.com',
            'password' => bcrypt('12345678')
        ])->assignRole('admin');

        User::create([
            'name' => 'Braun',
            'email' => 'Braun@correo.com',
            'password' => bcrypt('12345678')
        ])->assignRole('pedidos');
    }
}
