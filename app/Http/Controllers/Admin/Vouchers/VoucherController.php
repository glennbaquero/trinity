<?php

namespace App\Http\Controllers\Admin\Vouchers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Vouchers\VoucherStoreRequest;

use App\Models\Vouchers\Voucher;

class VoucherController extends Controller
{
    public function __construct() {
        $this->middleware('App\Http\Middleware\Admin\Vouchers\VoucherMiddleware', 
            ['only' => ['index', 'create', 'store', 'show', 'update', 'archive', 'restore']]
        );
    }
    /**
     * Display a listing of the vouchers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.vouchers.index', [
            //
        ]);
    }

    /**
     * Show the form for creating a new vouchers.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.vouchers.create', [
            //
        ]);
    }

    /**
     * Store a newly created vouchers in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VoucherStoreRequest $request)
    {

        $item = Voucher::store($request);

        $message = "You have successfully updated {$item->renderName()}";
        $action = 1;
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'action' => $action,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified vouchers.
     *
     * @param   \App\Model\Vouchers\Voucher  $item
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Voucher::withTrashed()->findOrFail($id);

        return view('admin.vouchers.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified voucher in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Vouchers\Voucher  $item
     * @return \Illuminate\Http\Response
     */
    public function update(VoucherStoreRequest $request, $id)
    {
        $item = Voucher::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->renderName()}";
        $action = 1;

        $item = Voucher::store($request, $item);

        return response()->json([
            'message' => $message,
            'action' => $action,
        ]);
    }

    /**
     * Remove the specified voucher from storage.
     *
     * @param  \App\Model\Vouchers\Voucher  $item
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = Voucher::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->renderName()}",
        ]);
    }

    /**
     * Restore the specified voucher from storage.
     *
     * @param  \App\Model\Vouchers\Voucher  $item
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = Voucher::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restore {$item->renderName()}",
        ]);
    }
}
