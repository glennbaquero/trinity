<?php

use Illuminate\Database\Seeder;

use App\Models\Areas\Area;

class AreasSeeder extends Seeder
{

	private $areas = [
		[
			'name' => 'Luzon'
		],
		[
			'name' => 'Visayas'
		],
		[
			'name' => 'Mindanao'
		],
		[
			'name' => 'NCR'
		],
		[
			'name' => 'Metro Manila'
		],		
	];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Area::truncate();

		foreach ($this->areas as $key => $area) {
			Area::create($area);
		}
    }
}
