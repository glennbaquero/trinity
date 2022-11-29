<?php

namespace App\Http\Controllers\Admin\BankDetails;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\BankDetails\BankDetailStoreRequest;
use App\Http\Controllers\Controller;

use App\Models\BankDetails\BankDetail;

use DB;

class BankDetailController extends Controller
{

    public function __construct() {
        $this->middleware('App\Http\Middleware\Admin\BankDetails\BankDetailMiddleware', 
            ['only' => ['index', 'create', 'update', 'archive', 'restore']]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.bank-details.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.bank-details.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankDetailStoreRequest $request)
    {
        DB::beginTransaction();
            $item = BankDetail::store($request);
        DB::commit();

        $message = "You have successfully created {$item->id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
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
        $item = BankDetail::withTrashed()->findOrFail($id);
        
        return view('admin.bank-details.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BankDetailStoreRequest $request, $id)
    {
        $item = BankDetail::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->title}";

        $item = BankDetail::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankDetail  $bankDetails
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = BankDetail::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived Bank Detail {$item->account_number}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\BankDetail  $bankDetail
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = BankDetail::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored Bank Detail {$item->account_number}",
        ]);
    }

}
