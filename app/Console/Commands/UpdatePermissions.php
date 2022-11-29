<?php

namespace App\Console\Commands;

use App\Models\Roles\Role;
use App\Models\Permissions\Permission;

class UpdatePermissions extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Permissions';

    /**
     * Command to run
     * @return void
     */
    protected function start()
    {
        $this->call('db:seed', [
            '--class' => '\App\Seeders\PermissionsTableSeeder',
        ]);

        Role::first()->syncPermissions(Permission::all());
    }
}
