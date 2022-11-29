<?php

namespace App\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Users\Admin;
use App\Models\Roles\Role;
use App\Models\Permissions\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'Super Admin'],
            ['name' => 'Admin'],
        ];

        foreach ($roles as $role) {
            $role = Role::updateOrCreate($role);
            $role->syncPermissions(Permission::all());
        }

        $admin = Admin::first();

        $admin->assignRole(Role::first());
    }
}
