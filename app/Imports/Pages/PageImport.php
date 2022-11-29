<?php

namespace App\Imports\Pages;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Helpers\StringHelpers;

use App\Models\Pages\Page;

class PageImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
    	foreach ($rows as $row) {
    		Page::updateOrCreate([
    			'slug' => StringHelpers::slugify($row['slug']),
    		], [
	            'name' => $row['name'],
    		]);
    	}
    }
}