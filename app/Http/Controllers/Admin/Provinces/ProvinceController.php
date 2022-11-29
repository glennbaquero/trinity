<?php

namespace App\Http\Controllers\Admin\Provinces;

use App\Models\Provinces\Province;

use App\Http\Requests\Admin\Provinces\ProvinceRequest;
use App\Http\Controllers\Controller;

class ProvinceController extends Controller
{
    
    /**
    * Show provinces table
    *
    * @return Illuminate\Http\Response
    */
	public function index()
	{
		return view('admin.provinces.index');
	}


    /**
    * Show create province form
    *
    * @return Illuminate\Http\Response
    */
	public function create()
	{
		return view('admin.provinces.create');
	}


    /**
    * Store a province to DB
    *
    * @param App\Http\Requests\Admin\Provinces\ProvinceRequest $request
    * @return Illuminate\Http\Response
    */
	public function store(ProvinceRequest $request)
	{
		\DB::beginTransaction();
		$item = Province::store($request);
		\DB::commit();

		$message = "You have successfully created {$item->id}!";
		$redirect = $item->renderShowUrl();

		return response()->json(compact('message', 'redirect'));
	}


    /**
    * View a specific province
    *
    * @param int $id
    * @return Illuminate\Http\Response
    */
	public function show(int $id)
    {
        $item = Province::withTrashed()->findOrFail($id);

        return view('admin.provinces.show', compact('item'));
    }


    /**
    * Update a specific province
    *
    * @param App\Http\Requests\Admin\Provinces\ProvinceRequest $request
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function update(ProvinceRequest $request, int $id)
    {
        $item = Province::withTrashed()->findOrFail($id);
        $message = "You have successfully updated Province #{$item->id}";

        $item = Province::store($request, $item);

        return response()->json(compact('message'));
    }


    /**
    * Archive a specific province
    *
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function archive(int $id)
    {
        $item = Province::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived Province #{$item->id}",
        ]);
    }


    /**
    * Restore a specific province
    *
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function restore(int $id)
    {
        $item = Province::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored Province #{$item->id}",
        ]);
    }

}
