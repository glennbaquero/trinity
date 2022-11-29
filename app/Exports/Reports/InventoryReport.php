<?php

namespace App\Exports\Reports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

use App\Models\Inventories\Inventory;

class InventoryReport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [

            BeforeSheet::class => function(BeforeSheet $event) {

                $event->sheet->setCellValue('A1', 'Report Type');
                $event->sheet->setCellValue('B1', 'Inventory Report');
            },
        ];
    }


    /**
     * Setting up headings
     * 
     * @return array
     */
    public function headings() : array
    {  

    	return [
    		'Product ID',
    		'SKU',
    		'Product Name',
    		'Stocks left',
    		'Stocks status',
    		'Updated at'
    	];

    }  

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$collections = [];

    	$inventories = Inventory::get();

		$collections = collect($this->formatColumns($inventories));
		
		return $collections;
    }

    /**
     * Formatting columns
     * 
     * @return array
     */
    public function formatColumns($inventories, $reports = [])
    {
    	foreach ($inventories as $key => $inventory) {
    		$columns = [
    			'product_id' => $inventory->product->id,
    			'sku' => $inventory->product->sku,
    			'product_name' => $inventory->product->name,
    			'stocks_left' => $inventory->stocks,
    			'stocks_status' => $inventory->renderStatus()['label'],
    			'updated_at' => $inventory->updated_at->toDayDateTimeString()
    		];
    		array_push($reports, $columns);
    	}
    	return $reports;
    }

}
