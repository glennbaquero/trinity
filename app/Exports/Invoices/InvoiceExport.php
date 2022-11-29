<?php

namespace App\Exports\Invoices;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\Invoices\Invoice;

class InvoiceExport implements FromArray, WithStrictNullComparison, WithHeadings, ShouldAutoSize
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
    		foreach ($item->invoiceItems as $invoice_item) {
    			$result[] = [
	                '#' => $item->id,
	                'reference_code' => $item->code,
	                'invoice_number' => $item->invoice_number,
	                'shipping_name' => $item->shipping_name,
	                'shipping_email' => $item->shipping_email,
	                'contact_number' => $item->shipping_mobile,
	                'shipping_unit' => $item->shipping_unit,
	                'shipping_street' => $item->shipping_street,
	                'shipping_region' => $item->shipping_region,
	                'shipping_province' => $item->shipping_province,
	                'shipping_city' => $item->shipping_city,
	                'shipping_zip' => $item->shipping_zip,
	                'shipping_landmark' => $item->shipping_landmark,
	                'product' => json_decode($invoice_item->data)->name,
	                'product_price' => $invoice_item->price,
	                'quantity' => $invoice_item->quantity,
	                'total_price (Quantity x Product Price)' => $invoice_item->total_price,
	                'status' => $item->status->name,
	                'updated_at' => $item->renderDate('updated_at'),
	                'shipping_fee' => $item->shipping_fee,
	                'discount' => $item->discount,
	                'sub_total' => $item->sub_total,
	                'total' => $item->grand_total,
	            ];
    		}
    	}

        return $result;
    }

    public function headings(): array
    {
        return [
        	'#',
            'Reference Code',
	        'Invoice Number',
	        'Shipping Name',
	        'Shipping Email',
	        'Contact Number',
	        'Shipping Unit',
	        'Shipping Street',
	        'Shipping Region',
	        'Shipping Province',
	        'Shipping City',
	        'Shipping Zip',
	        'Shipping Landmark',
	        'Product',
	        'Product Price',
	        'Quantity',
	        'Total Price (Quantity x Product Price',
	        'Status',
	        'Status Update',
	        'Shipping Fee',
	        'Discount',
	        'Sub Total',
	        'Total',
        ];
    }
}
