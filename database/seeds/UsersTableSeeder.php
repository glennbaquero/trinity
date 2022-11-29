<?php

namespace App\Seeders;

use Illuminate\Database\Seeder;
use Hash;
use Carbon\Carbon;
use App\Helpers\SeederHelpers;
use App\Models\Users\User;

class UsersTableSeeder extends Seeder
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
        $this->command->info('Seeding Web Users...');

        $this->users = array_merge($this->users, [
            [
                'first_name' => 'Test',
                'last_name' =>  'User',
                'image_path' => SeederHelpers::randomFile(),
                'email' => 'web@app.com',
                'password' => 'password',
            ],
        ]);

        foreach($this->users as $account) {
            User::create([
                'first_name' => $account['first_name'],
                'last_name' => $account['last_name'],
                'image_path' => $account['image_path'],
                'mobile_number' => '09123456789',
                'password' => Hash::make($account['password']),
                'email' => $account['email'],
                'approved' => 1,
            ]);
        }
    }

}
