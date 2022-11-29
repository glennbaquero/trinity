<?php

namespace App\Http\Controllers\Admin\Invoices;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\Invoices\InvoiceFailedTransactionStorePost;
use App\Http\Controllers\Admin\Invoices\InvoiceFetchController;
use App\Http\Controllers\Controller;

use App\Ecommerce\PaynamicsProcessor;

use App\Models\Invoices\Invoice;
use App\Models\StatusTypes\StatusType;

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

use Maatwebsite\Excel\Facades\Excel;

use App\Exports\Invoices\InvoiceExport;

use App\Notifications\Care\CancelInvoice;
use App\Notifications\Care\UpdateInvoice;

use App\Models\Users\User;

use Carbon\Carbon;

use App\Services\PushService;

class InvoiceController extends Controller
{

    public function __construct() {
        $this->middleware('App\Http\Middleware\Admin\Invoices\InvoiceMiddleware', 
            ['only' => ['index', 'update', 'failedTransactionForm', 'export', 'archive', 'restore']]
        );
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = StatusType::orderBy('order', 'asc')->get()
                    ->map(function($status) {
                        return $status->only(['id', 'name']);
                    });

        return view('admin.invoices.index', compact('statuses'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($invoiceNumber, $id)
    {
        $invoice = Invoice::find($id);
        
        $statuses = StatusType::orderBy('order', 'asc')->get()
                    ->map(function($status) {
                        return $status->only(['id', 'name']);
                    });        

        return view('admin.invoices.show', compact('invoice', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $invoice = Invoice::find($id);

        $user = User::find($invoice->user_id);

        if($status = StatusType::isActionCancel($request->status)) {
            return response()->json([
                'redirectUrl' => $invoice->renderFailedTransactionFormUrl($status->id),
            ]);
        }

        /** Start transaction */
        \DB::beginTransaction();

            $vars = [
                'status_id' => $request->status,
                'payment_status' => $invoice->checkIfCompleted($request->status),
                'completed' => StatusType::isActionCompleted($request->status)
            ];

            if ($vars['payment_status'] && !$invoice->payment_status) {
                $invoice->distributePoints();
                if($user->firstTimeBuyer()) {
                    $user->successfulReferral($invoice->id);
                }
            }

            /** Update invoice */
            $invoice->update($vars);


            /**
             * Create customize logs
             * 
             */
            $causer = \Auth::guard('admin')->user()->id;
            $statusName = $invoice->status->name;
            $logMessage = "Invoice has been updated to {$statusName}";
            $invoice->createLog($causer, $logMessage);

        /** End transaction */
        \DB::commit();

        $user->notify(new UpdateInvoice($request,$invoice));

        $push = new PushService('Order status changed', "Your order with invoice number : {$invoice->invoice_number} has been updated to {$statusName}.");
        $push->pushToOne($user);

        $message = "Successfully updated the status invoice #{$invoice->invoice_number}.";

        return response()->json(compact('message'));

    }


    /**
     * Display failed transaction form
     * 
     * @param  Request $request       
     * @param  String  $invoiceNumber
     * @param  int  $id
     */
    public function failedTransactionForm(Request $request, $invoiceNumber, $id)
    {
        $invoice = Invoice::find($id);
        $status = StatusType::find($request->type);

        return view('admin.invoices.failed.failed-transaction', compact('invoice', 'status'));
    }

    /**
     * Store failed transaction details for specified invoice
     * 
     * @param  InvoiceFailedTransactionStorePost $request
     * @param  int                            $id      [description]
     * @param  int                            $type    [description]
     */
    public function failedTransactionSubmit(InvoiceFailedTransactionStorePost $request, $id, $type)
    {

        $invoice = Invoice::find($id);
        $redirectUrl = $invoice->renderShowUrl();
        $user = User::find($invoice->user_id);

        $vars = $request->except(['']);

        /** Start transaction */
        \DB::beginTransaction();

            /** Update invoice */
            $invoice->update(['status_id' => $type, 'completed' => 1 ]);

            /** Create failed transaction */
            $invoice->failed_transaction()->create($vars);

            /** Return stocks */
            $invoice->returningStocks();


            /**
             * Create customize logs
             * 
             */
            $causer = \Auth::guard('admin')->user()->id;
            $logMessage = "Invoice has been updated to {$request->type}";
            $invoice->createLog($causer, $logMessage);

        /** End transaction */
        \DB::commit();    

        $user->notify(new CancelInvoice($request, $invoice));

        $push = new PushService('Your order is cancelled', "Your order with an invoice number : {$invoice->invoice_number} has been cancelled. {$request->reason}");
        $push->pushToOne($user);

        return response()->json([
            'redirect' => $redirectUrl
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $invoice = Invoice::find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Soft delete invoice */
            $invoice->archive();

        /** End transaction */
        \DB::commit();

        return response()->json([
            'message' => "You have successfully archived {$invoice->name}",
        ]);
    }

    /**
     * Restore the specified resource from storage
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {

        $invoice = Invoice::onlyTrashed()->find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Recover/Restore invoice */
            $invoice->unarchive();

        /** End transaction */
        \DB::commit();        

        return response()->json([
            'message' => "You have successfully restored {$invoice->name}",
        ]);

    }


    /**
     * Processing paynamics
     * 
     * @param  Requests $request 
     */
    public function processPaynamics(Request $request)
    {
        $processor = new PaynamicsProcessor();

        /** Process Paynamics */
        return $processor->process($request);
    }

    /**
     * Paynamics success return
     * 
     */
    public function paynamicsReturn(Request $request)
    {
        $processor = new PaynamicsProcessor();
        $route = $processor->processReturnResponse($request);

        return response()->json([

        ]);
    }

    /**
     * Paynamics cancel
     * 
     */
    public function paynamicsCancel()
    {
        return response()->json([

        ]);
    }

    /*
     * Export Selected Invoices
     * 
     */
    public function export(Request $request) 
    {
        $controller = new InvoiceFetchController;

        $request = $request->merge(['nopagination' => 1]);

        $data = $controller->fetch($request);
        $message = 'Exporting data, please wait...';

        if (!count($data)) {
            throw ValidationException::withMessages([
                'items' => 'No invoices found.',
            ]);
        }

        if (!$request->ajax()) {
            $ids = Arr::pluck($data, 'id');
            $data = Invoice::whereIn('id', $ids)->get();
            return Excel::download(new InvoiceExport($data), 'Invoices_' . Carbon::now()->toDateTimeString() . '.xls');
        }

        if ($request->ajax()) {
            return response()->json(compact('message'));
        }
    }

    /**
     * Printing of invoice to
     * 
     * @param  string $invoiceNumber
     * @param  int $id
     */
    public function printInvoice($invoiceNumber, $id)
    {   
        $invoice = Invoice::withTrashed()->find($id);

        $pdf = \PDF::loadView('admin.invoices.forms.print', [
            'invoice' => $invoice
        ]);

        return $pdf->stream();
    }
}
