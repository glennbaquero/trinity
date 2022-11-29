<?php

namespace App\Seeders\Samples;

use App\Seeders\AdminsTableSeeder;

use App\Models\Users\Admin;

use App\Helpers\SeederHelpers;

class SampleAdminsTableSeeder extends AdminsTableSeeder
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
        factory(Admin::class, 12)->create();
    }

    protected function setArray() 
    {
        /* Put additional users here */
        $this->users = [];
    }
}
