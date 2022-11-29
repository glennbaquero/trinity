<?php

namespace App\Exports\Reports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

use App\Models\CreditInvoices\CreditInvoice;
use App\Models\Users\User;

class TransactionHistoryReport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
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
                $event->sheet->setCellValue('B1', 'Transaction History Report');

                $event->sheet->setCellValue('A3', 'Start Date');
                $event->sheet->setCellValue('B3', $this->requestData['date_range.start_date']);

                $event->sheet->setCellValue('A4', 'End Date');
                $event->sheet->setCellValue('B4', $this->requestData['date_range.end_date']);
                
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
            'User',
            'Credit Package',
    		'Payment Type',
    		'Response',
    		'Invoice Number',
            'Reference Number',
            'Total',
            'Status',
            'Created Date',
    	];

    }  

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$collections = [];

    	$credit_invoices = CreditInvoice::whereBetween('created_at', [$this->requestData['date_range.start_date'], $this->requestData['date_range.end_date']])->get();

		$collections = collect($this->formatColumns($credit_invoices));
		
		return $collections;
    }


    /**
     * Formatting columns
     * 
     * @return array
     */
    public function formatColumns($credit_invoices, $reports = [])
    {
    	foreach ($credit_invoices as $key => $credit_invoice) {
                $columns = [
                    'user_id' => $credit_invoice->renderUserName(),
                    'credit_package_id' => $credit_invoice->renderPackageName(),
                    'payment_type' => $credit_invoice->renderPaymentType(),
                    'response' => $credit_invoice->response,
                    'invoice_number' => $credit_invoice->invoice_number,
                    'reference_code' => $credit_invoice->reference_code,
                    'total' => $credit_invoice->total,
                    'status' => $credit_invoice->renderStatus(),
                    'created_at' => $credit_invoice->created_at,
                ];
                array_push($reports, $columns);
    	}
    	return $reports;
    }

}
