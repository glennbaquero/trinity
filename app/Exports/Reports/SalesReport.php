<?php

namespace App\Exports\Reports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

use App\Models\Invoices\Invoice;

class SalesReport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
	protected $requestData;

	public function __construct($requests)
    {
        $this->requestData = $requests;
    }


    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [

            BeforeSheet::class => function(BeforeSheet $event) {

                $event->sheet->setCellValue('A1', 'Report Type');
                $event->sheet->setCellValue('B1', 'Sales Report');

                $event->sheet->setCellValue('A3', 'Start Date');
                $event->sheet->setCellValue('B3', $this->requestData['start_date']);

                $event->sheet->setCellValue('A4', 'End Date');
                $event->sheet->setCellValue('B4', $this->requestData['end_date']);

                $event->sheet->setCellValue('A5', 'Total Sales');
                $event->sheet->setCellValue('B5', Invoice::renderTotalSales($this->requestData, true));

                $event->sheet->setCellValue('A6', '');
                $event->sheet->setCellValue('A7', '');

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
    		'Order Number',
            'User',
            'Product',
            'Per Product Quantity',
    		'Status',
    		'Total Quantity',
    		'Total Payment',
    		'Payment Type',
    		'Checkout At'
    	];

    }  

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$collections = [];

    	$invoices = Invoice::where('payment_status', Invoice::PAID)
    				->whereBetween('created_at', [$this->requestData['start_date'], $this->requestData['end_date']])->get();

		$collections = collect($this->formatColumns($invoices));
		
		return $collections;
    }


    /**
     * Formatting columns
     * 
     * @return array
     */
    public function formatColumns($orders, $reports = [])
    {
    	foreach ($orders as $key => $order) {
            foreach ($order->invoiceItems as $item) {
                $columns = [
                    'order_number' => $order->invoice_number,
                    'user' => $order->user->renderFullName(),
                    'product' => json_decode($item->data)->name,
                    'quantity' => $item->quantity,
                    'status' => $order->status->name,
                    'total_quantity' => $order->renderTotalItemsBought(),
                    'total_payment' => 'Php ' . number_format($order->grand_total, 2, '.', ','),
                    'payment_method' => $order->renderPaymentType('label'),
                    'checkout_at' => $order->updated_at->toDayDateTimeString()
                ];
                array_push($reports, $columns);
            }
    	}
    	return $reports;
    }

}
