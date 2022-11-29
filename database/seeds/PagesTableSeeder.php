<?php

namespace App\Seeders;

use Illuminate\Database\Seeder;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

use App\Imports\Pages\PageImport;
use App\Imports\Pages\PageItemImport;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::beginTransaction();

        Excel::import(new PageImport, storage_path('imports/pages.xls'));
        Excel::import(new PageItemImport, storage_path('imports/page_items.xls'));

        DB::commit();
    }
}
