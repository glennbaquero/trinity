<?php

namespace App\Imports\Locations;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Models\Regions\Region;
use App\Models\Provinces\Province;

class LocationImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
    	foreach ($rows as $row) {
            $region = Region::where('name', $row['region'])->first();

            if (!$region) {
                $region = Region::create(['name' => $row['region']]);
            }

            // print('Region: ' . $row['region']);
            // print("\n");

            $region->provinces()->updateOrCreate([
                'name' => $row['province'],
            ]);

            $province = Province::where('name', $row['province'])->first();

            // print('Province: ' . $row['province']);
            // print("\n");

            $province->standard()->updateOrCreate([
                'fee' => $row['standard'],
            ]);

            // print('Standard Fee: ' . $row['standard']);
            // print("\n");

            $province->cities()->updateOrCreate([
                'name' => $row['city'],
            ]);

            // print('City: ' . $row['city']);
            // print("\n");

            foreach($province->cities as $cities) {
                $cities->express()->updateOrCreate([
                    'fee' => $row['express'],
                ]);
            }

            // print('Express Fee: ' . $row['express']);
            // print("\n");
            // print("===============================================================\n");
    	}
    }
}