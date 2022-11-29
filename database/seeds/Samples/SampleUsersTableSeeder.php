<?php

namespace App\Seeders\Samples;

use App\Seeders\UsersTableSeeder;

use App\Models\Users\User;

use App\Helpers\SeederHelpers;

class SampleUsersTableSeeder extends UsersTableSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setArray();
        $this->populate();
        factory(User::class, 12)->create();
    }

    protected function setArray() 
    {
        /* Put additional users here */
        $this->users = [];
    }
}
