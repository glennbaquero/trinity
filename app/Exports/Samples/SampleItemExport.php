<?php

namespace App\Exports\Samples;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SampleItemExport implements FromArray, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function array(): array
    {
    	$result = [];

    	foreach ($this->items as $item) {
    		$result[] = [
                '#' => $item->id,
                'Name' => $item->renderName(),
                'Created Date' => $item->renderDate(),
                'Description' => strip_tags($item->description),
                'Status' => $item->renderStatus(),
            ];
    	}

        return $result;
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Created Date',
            'Description',
            'Status',
        ];
    }
}