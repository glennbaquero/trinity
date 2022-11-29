<?php

namespace App\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Regions\Region;

class RegionsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::truncate();

        $regions = ['NCR', 'Region 8', 'Region 10'];

        foreach ($regions as $region) {
        	Region::create(['name' => $region]);
        }
    }

}
