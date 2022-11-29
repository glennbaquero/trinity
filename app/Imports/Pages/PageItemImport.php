<?php

namespace App\Imports\Pages;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Storage;
use App\Helpers\StringHelpers;

use App\Models\Pages\Page;
use App\Models\Pages\PageItem;

class PageItemImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
    	foreach ($rows as $row) {
    		$page = Page::where('slug', $row['parent_slug'])->first();

    		PageItem::updateOrCreate([
    			'slug' => StringHelpers::slugify($row['slug']),
    		], [
	            'name' => $row['name'],
                'content' => $row['content'],
	            'type' => $row['type'],
	            'page_id' => $page->id,
    		]);
    	}
    }
}