<?php

namespace App\Http\Controllers\Samples;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Samples\SampleItemFetchController;

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;
use App\Http\Requests\Samples\SampleItemStoreRequest;
use App\Http\Requests\Samples\SampleItemDenyRequest;

use App\Exports\Samples\SampleItemExport;
use Carbon\Carbon;
use Excel;

use App\Models\Samples\SampleItem;

class SampleItemController extends Controller
{
    protected $indexView;
    protected $createView;
    protected $showView;
    protected $guard = 'admin';
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filterStatus = json_encode(SampleItem::getStatus());

        return view($this->indexView, [
            'filterStatus' => $filterStatus,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->createView, [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SampleItemStoreRequest $request)
    {
        $item = SampleItem::store($request);

        $message = "You have successfully updated {$item->renderName()}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SampleItem  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $item = SampleItem::withTrashed()->findOrFail($id);

        return view($this->showView, [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SampleItem  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(SampleItemStoreRequest $request, $id)
    {
        $item = SampleItem::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->renderName()}";

        $item = SampleItem::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SampleItem  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = SampleItem::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->renderName()}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\SampleItem  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = SampleItem::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->renderName()}",
        ]);
    }

    public function removeImage(Request $request, $id)
    {
        $item = SampleItem::withTrashed()->findOrFail($id);
        $message = 'You have successfully remove the image in sample item #' . $item->id;

        $result = $item->removeImage($request);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function approve(Request $request, $id)
    {
        $item = SampleItem::withTrashed()->findOrFail($id);

        $item->approve($request);
        $message = "You has been marked as approved {$item->renderName()}";

        return response()->json([
            'message' => $message,
        ]);
    }

    public function deny(SampleItemDenyRequest $request, $id)
    {
        $item = SampleItem::withTrashed()->findOrFail($id);

        $item->deny($request);
        $message = "You has been marked as denied {$item->renderName()}";

        return response()->json([
            'message' => $message,
        ]);
    }

    public function export(Request $request)
    {
        $controller = new SampleItemFetchController;

        $request = $request->merge(['nopagination' => 1]);

        $data = $controller->fetch($request);
        
        $message = 'Exporting data, please wait...';

        if (!count($data)) {
            throw ValidationException::withMessages([
                'items' => 'No sample items found.',
            ]);
        }

        if (!$request->ajax()) {
            $ids = Arr::pluck($data, 'id');
            $data = SampleItem::whereIn('id', $ids)->get();
            return Excel::download(new SampleItemExport($data), 'Samples_' . Carbon::now()->toDateTimeString() . '.xls');
        }

        if ($request->ajax()) {
            return response()->json([
                'message' => $message,
            ]);
        }
    }
}
