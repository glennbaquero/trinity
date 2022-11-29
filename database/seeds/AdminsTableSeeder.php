<?php

namespace App\Seeders;

use Illuminate\Database\Seeder;
use Hash;
use App\Helpers\SeederHelpers;
use App\Models\Users\Admin;

class AdminsTableSeeder extends Seeder
{
    protected $users;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setArray();
        $this->populate();   
    }

    protected function setArray() 
    {
        $this->users = [];
    }

    protected function populate() 
    {
        $this->command->info('Seeding Admin Users...');

        $this->users = array_merge($this->users, [
            [
                'first_name' => 'App',
                'last_name' => 'Admin',
                'image_path' => SeederHelpers::randomFile(),
                'email' => 'admin@app.com',
                'password' => 'password',
            ],
        ]);

        foreach($this->users as $account) {
            Admin::create([
                'first_name' => $account['first_name'],
                'last_name' => $account['last_name'],
                'image_path' => $account['image_path'],
                'email' => $account['email'],
                'password' => Hash::make($account['password']),
            ]);
        }
    }

}
