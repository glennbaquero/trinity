<?php

namespace App\Http\Controllers\Admin\FaqCategories;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Faqs\FaqCategory;

class FaqCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.faq-categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.faq-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** Start transaction */
        \DB::beginTransaction();

            /** Store faq category */
            $item = FaqCategory::store($request);
            
        /** End transaction */
        \DB::commit();

        $message = "You have successfully created #{$item->id}";
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
        $item = FaqCategory::withTrashed()->find($id);

        return view('admin.faq-categories.show', [
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $item = FaqCategory::withTrashed()->find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Update faq category */
            $item = FaqCategory::store($request, $item);

        /** End transaction */
        \DB::commit();

        $message = "You have successfully update #{$item->id}";

        return response()->json([
            'message' => $message,
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
        $item = FaqCategory::find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Soft delete faq category */
            $item->archive();

        /** End transaction */
        \DB::commit();

        return response()->json([
            'message' => "You have successfully archived #{$item->id}",
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

        $item = FaqCategory::onlyTrashed()->find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Recover/Restore faq category */
            $item->unarchive();

        /** End transaction */
        \DB::commit();        

        return response()->json([
            'message' => "You have successfully restored #{$item->id}",
        ]);

    }
}
