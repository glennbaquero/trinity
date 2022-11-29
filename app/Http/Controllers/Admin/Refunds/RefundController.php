<?php

namespace App\Http\Controllers\Admin\Refunds;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Refunds\Refund;

use DB;

class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.refunds.index', [
        ]);
    }

     /**
     * Proceed to disapproval with reason input field
     *
     * @return \Illuminate\Http\Response
     */
    public function disapprovalForm($id)
    {
        $item = Refund::withTrashed()->findOrFail($id);
        
        return view('admin.refunds.disapproval-form', [
            'item' => $item,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Refund::find($id);

        return view('admin.refunds.show', [
            'item' => $item
        ]);
    }

    /**
     * Disapprove specified resource from storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Refund::find($id);
        $consultation_id = $item->consultation_id;

        /** Start transaction */
        DB::beginTransaction();

            /** Update item */
            $updateRefund = Refund::store($request, $item);
            
            /** Set disapprover */
            $item->updateStatus(false, $consultation_id, $request->disapproved_reason);

        /** End transaction */
        DB::commit();

        $message = 'You have successfully disapproved the refund request';
        $redirect = route('admin.refunds.index');

        return response()->json([
            'message' => $message,
            'redirect' => $redirect
        ]);
    }

     /**
     * Approve specified resource from storage
     * 
     * @param  int $id
     */
    public function approve($id)
    {
        $item = Refund::withTrashed()->findOrFail($id);       
        $consultation_id = $item->consultation_id;
        
        /** Start transaction */
        DB::beginTransaction();

            $item->updateStatus(true, $consultation_id);

            //Credit refund
            $item->user->processCredit($item->consultation->consultation_fee);

            // amount deducted to doctor for refund
            $amount = 0 - $item->consultation->consultation_fee;
            $item->doctor->processCredit($amount);

        /** End transaction */
        DB::commit();

        return response()->json([
            'message' => "You have successfully approved the refund request",
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = Refund::findOrFail($id);
  
        /** Start transaction */
        DB::beginTransaction();
                
            /** Archive item */
            $item->archive();

        /** End transaction */
        DB::commit();

        return response()->json([
            'message' => "You have successfully archived the refund request",
        ]);
    }


    /**
     * Restore the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = Refund::onlyTrashed()->findOrFail($id);
        
        /** Start transaction */
        DB::beginTransaction();
                
            /** Restore item */
            $item->restore();

        /** End transaction */
        DB::commit();

        return response()->json([
            'message' => "You have successfully restored the refund request",
        ]);
    }
}
