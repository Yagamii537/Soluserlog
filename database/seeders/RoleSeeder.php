<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'pedidos']);

        Permission::create(['name' => 'dash.index'])->syncRoles([$role1, $role2]);

        Permission::create(['name' => 'users.index'])->syncRoles([$role1]);
        Permission::create(['name' => 'users.edit'])->syncRoles([$role1]);
        Permission::create(['name' => 'users.update'])->syncRoles([$role1]);

        Permission::create(['name' => 'orders.index'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'orders.create'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'orders.edit'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'orders.destroy'])->syncRoles([$role1, $role2]);

        Permission::create(['name' => 'clientes.index'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'clientes.create'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'clientes.edit'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'clientes.destroy'])->syncRoles([$role1, $role2]);
    }
}
