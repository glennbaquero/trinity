<?php

namespace App\Seeders;

use App\Models\Regions\Region;

use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = ['ARMM (Autonomous Region in Muslim Mindanao)', 'CAR (Cordillera Administrative Region)', 'NCR (National Capital Region)', 'Region 1 (Ilocos Region)', 'Region 2 (Cagayan Valley)', 'Region 3 (Central Luzon)', 'Region 4A (CALABARZON)', 'Region 4B (MIMAROPA)', 'Region 5 (Bicol Region)', 'Region 6 (Western Visayas)', 'Region 7 (Central Visayas)', 'Region 8 (Eastern Visayas)', 'Region 9 (Zamboanga Peninsula)', 'Region 10 (Northern Mindanao)', 'Region 11 (Davao Region)', 'Region 12 (SOCCSKSARGEN)', 'Region 13 (Caraga Region)'];

        Region::truncate();

        foreach ($regions as $region) {
        	Region::create(['name' => $region]);
        }
    }
}
