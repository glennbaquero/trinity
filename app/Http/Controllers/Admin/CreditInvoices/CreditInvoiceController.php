<?php

namespace App\Http\Controllers\Admin\CreditInvoices;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\CreditInvoices\CreditInvoice;

use DB;

class CreditInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.credit-invoices.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $invoice = CreditInvoice::find($id);

        /** Start transaction */
        DB::beginTransaction();

            /** Soft delete invoice */
            $invoice->archive();

        /** End transaction */
        DB::commit();

        return response()->json([
            'message' => "You have successfully archived {$invoice->invoice_number}",
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

        $invoice = CreditInvoice::onlyTrashed()->find($id);

        /** Start transaction */
        DB::beginTransaction();

            /** Recover/Restore invoice */
            $invoice->unarchive();

        /** End transaction */
        DB::commit();        

        return response()->json([
            'message' => "You have successfully restored {$invoice->invoice_number}",
        ]);

    }
}
