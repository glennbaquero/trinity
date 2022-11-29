<?php

namespace App\Http\Controllers\Admin\Payouts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Payouts\Payout;
use Illuminate\Validation\ValidationException;

use DB;

class PayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.payouts.index', [
        ]);
    }

     /**
     * Proceed to disapproval with reason input field
     *
     * @return \Illuminate\Http\Response
     */
    public function disapprovalForm($id)
    {
        $item = Payout::withTrashed()->findOrFail($id);
        
        return view('admin.payouts.disapproval-form', [
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
        $item = Payout::find($id);

        return view('admin.payouts.show', [
            'item' => $item
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Disapprove specified resource from storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function disapprove(Request $request, $id)
    {
        $item = Payout::find($id);

        /** Start transaction */
        DB::beginTransaction();

            /** Update item */
            $item = Payout::store($request, $item);
            /** Set disapprover */
            $item->updateStatus(false);

        /** End transaction */
        DB::commit();

        $message = 'You have successfully disapproved the payout request';
        $redirect = route('admin.payouts.index');

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
        $item = Payout::withTrashed()->findOrFail($id);       

        if(!$item->doctor->checkCredits($item->value)) {
            throw ValidationException::withMessages([
                'credits' => ['Unable to approve the payout request due to insufficient credits.']
            ]);
        }
        
        /** Start transaction */
        DB::beginTransaction();

            $item->updateStatus(true);
            $item->doctor->processCredit(-$item->value);

        /** End transaction */
        DB::commit();

        return response()->json([
            'message' => "You have successfully approved the payout request",
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = Payout::findOrFail($id);
  
        /** Start transaction */
        DB::beginTransaction();
                
            /** Archive item */
            $item->archive();

        /** End transaction */
        DB::commit();

        return response()->json([
            'message' => "You have successfully archived payout request",
        ]);
    }


    /**
     * Restore the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = Payout::onlyTrashed()->findOrFail($id);
        
        /** Start transaction */
        DB::beginTransaction();
                
            /** Restore item */
            $item->restore();

        /** End transaction */
        DB::commit();

        return response()->json([
            'message' => "You have successfully restored {$item->name}",
        ]);
    }
}
