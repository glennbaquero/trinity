<?php

namespace App\Seeders\Samples;
use Illuminate\Database\Seeder;

use App\Models\Pages\Page;
use App\Models\Pages\PageItem;

class SamplePagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Page::class, 3)->create()->each(function($item) {
        	$item->page_items()->saveMany(factory(PageItem::class, 5)->make());
        });
    }
}
