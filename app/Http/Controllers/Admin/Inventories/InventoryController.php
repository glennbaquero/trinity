<?php

namespace App\Http\Controllers\Admin\Inventories;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Inventories\Inventory;

class InventoryController extends Controller
{

    public function __construct() {
        $this->middleware('App\Http\Middleware\Admin\Inventories\InventoryMiddleware', 
            ['only' => ['index', 'update']]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.inventories.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inventory = Inventory::find($id);

        return view('admin.inventories.show', [
            'inventory' => $inventory,
        ]);
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

        $inventory = Inventory::find($id);

        if($inventory) {

            /** Start transaction */
            \DB::beginTransaction();

                /** Update inventory */
                $inventory->increment('stocks', $request->stocks); 
                
                /**
                 * Create customize logs
                 * 
                 */
                $incrementedStocks = $request->stocks + $inventory->stocks;
                $logMessage = 'Updated the stocks from '. $inventory->stocks . ' to ' . $incrementedStocks;
                $causer = \Auth::guard('admin')->user()->id;
                $inventory->createLog($causer, $logMessage);

            /** End transaction */
            \DB::commit();

        }

        $message = "You have successfully updated the stocks of {$inventory->product->name}";

        return response()->json([
            'message' => $message,
        ]);        

    }

    /**
     * Get status report
     * 
     * @return \Illuminate\Http\Response
     */
    public function getStatusReport()
    {
        $inventory_reports = Inventory::fetchInventoryStatusReport();
        return response()->json([
            'inventory_reports' => $inventory_reports,
        ]);
    }

}
