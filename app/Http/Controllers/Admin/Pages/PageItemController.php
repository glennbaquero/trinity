<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Pages\PageItemStoreRequest;

use App\Models\Pages\PageItem;

class PageItemController extends Controller
{
    public function __construct() {
        $this->middleware('App\Http\Middleware\Admin\Pages\PageItemMiddleware', 
            ['only' => ['index', 'create', 'store', 'show', 'update', 'archive', 'restore']]
        );
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.page-items.index', [
            //
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.page-items.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PageItemStoreRequest $request)
    {
        $item = PageItem::store($request);

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
     * Display the specified resource.
     *
     * @param  \App\Page  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = PageItem::withTrashed()->findOrFail($id);

        return view('admin.page-items.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(PageItemStoreRequest $request, $id)
    {
        $item = PageItem::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->renderName()}";
        $action = 1;

        $item = PageItem::store($request, $item);

        return response()->json([
            'message' => $message,
            'action' => $action,
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
        $item = PageItem::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->renderName()}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\PageItem  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = PageItem::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restore {$item->renderName()}",
        ]);
    }
}
