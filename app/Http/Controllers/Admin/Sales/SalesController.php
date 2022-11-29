<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Models\Invoices\Invoice;
use App\Models\StatusTypes\StatusType;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Reports\SalesReport;
use App\Exports\Reports\InventoryReport;
use App\Exports\Reports\AcceptedConsultationReport;
use App\Exports\Reports\DeclinedConsultationReport;
use App\Exports\Reports\TransactionHistoryReport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalesController extends Controller
{

	public function months()
	{
		return [
			1 => 'January',
			2 => 'February',
			3 => 'March',
			4 => 'April',
			5 => 'May',
			6 => 'June',
			7 => 'July',
			8 => 'August',
			9 => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December',
		];
	}
    
	public function get()
	{
		$invoices = Invoice::all();
		$sales = [];

		foreach ($this->months() as $key => $month) {
			$item = Invoice::where([
						'completed' => 1,
						'payment_status' => Invoice::PAID
					])
					->whereMonth('created_at', $key)
					->whereYear('created_at', date('Y'));

			$sales[$month] = $item->count() ? $item->sum('grand_total') : null;
		}

		return response()->json(compact('sales'));
	}

	public function index()
	{
		return view('admin.reports.index');
	}


	public function generateReport(Request $request)
	{
		if($request->type == 1) {
			return Excel::download(new SalesReport($request->date_range), 'sales-reports.xlsx');
		} 

		if($request->type == 2) {
			return Excel::download(new InventoryReport, 'inventory-reports.xlsx');
		}

		if($request->type == 3) {
			return Excel::download(new AcceptedConsultationReport($request), 'accepted-consultation-reports.xlsx');
		}

		if($request->type == 4) {
			return Excel::download(new DeclinedConsultationReport($request), 'declined-consultation-reports.xlsx');
		}

		if($request->type == 5) {
			return Excel::download(new TransactionHistoryReport($request), 'transaction-history-reports.xlsx');
		}

	}

}
