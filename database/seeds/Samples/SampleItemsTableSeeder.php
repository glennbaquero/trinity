<?php

namespace App\Seeders\Samples;

use Illuminate\Database\Seeder;
use App\Models\Samples\SampleItem;
use App\Models\Samples\SampleItemImage;

class SampleItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(SampleItem::class, 12)->create()->each(function($item) {
        	$item->images()->saveMany(factory(SampleItemImage::class, 2)->make());
        });
    }
}
