<?php

namespace App\Seeders;

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

use App\Imports\Products\ProductsImport;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Request $request)
    {
		Excel::import(new ProductsImport($request), storage_path('imports/products.xls'));
    }
}
