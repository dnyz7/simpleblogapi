<?php

namespace Database\Seeders;

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
        // $super = Role::create([
        //     'name' => 'super-admin',
        //     'guard_name' => 'web'
        // ]);

        // $permissions = Permission::pluck('id', 'id')->all();
        // $super->syncPermissions($permissions);

        $admin = Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        $adminPermission = Permission::pluck('id', 'id')->all();
        $admin->syncPermissions($adminPermission);

        $manager = Role::create([
            'name' => 'manager',
            'guard_name' => 'web'
        ]);

        $managerPermission = Permission::pluck('id', 'id')->all();
        $manager->syncPermissions($managerPermission);
        $manager->revokePermissionTo('user-create');
        $manager->revokePermissionTo('user-edit');
        $manager->revokePermissionTo('user-list');
        $manager->revokePermissionTo('user-delete');

        $user = Role::create([
            'name' => 'user',
            'guard_name' => 'web'
        ]);

        $userPermission = Permission::pluck('id', 'id')->all();
        $user->syncPermissions($userPermission);
        $user->revokePermissionTo('user-create');
        $user->revokePermissionTo('user-edit');
        $user->revokePermissionTo('user-list');
        $user->revokePermissionTo('user-delete');
    }
}
