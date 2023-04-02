<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super = User::create([
            'email' => 'admin@mail.com',
            'name'  => 'Admin',
            'password' => \Hash::make('admin123456'),
            'roles' => 'admin',
        ]);

        // $permissions = Permission::pluck('id', 'id')->all();
        // $role->syncPermissions($permissions);
        $super->assignRole('admin');

        $admin = User::create([
            'email' => 'manager@mail.com',
            'name'  => 'Manager',
            'password' => \Hash::make('manager123456'),
            'roles' => 'manager',

        ]);

        $admin->assignRole('manager');
        Permission::findById(1);
    }
}
