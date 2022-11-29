<?php

namespace App\Seeders\Samples;

use Illuminate\Database\Seeder;

use App\Models\Samples\SampleItem;

class SampleItemRelationshipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = SampleItem::all();

        foreach ($items as $item) {
        	$item->sample_item_id = SampleItem::inRandomOrder()->first()->id;
        	$item->data = SampleItem::inRandomOrder()->take(3)->pluck('id')->toArray();
        	$item->save();
        }
    }
}
