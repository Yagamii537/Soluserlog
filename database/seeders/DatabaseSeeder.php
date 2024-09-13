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

        User::create([
            'name' => 'Mauricio Peñafiel',
            'email' => 'admin@correo.com',
            'password' => bcrypt('12345678')
        ]);
        Cliente::factory(10)->create();

        Order::factory()->count(10)->create();

        $this->call([
            DocumentSeeder::class
        ]);
    }
}
