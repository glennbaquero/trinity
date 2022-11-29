<?php

namespace App\Http\Controllers\Admin\Rewards;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\Rewards\RewardStoreRequest;

use App\Models\Rewards\Reward;
use App\Models\Rewards\Sponsorship;
use App\Models\Users\Doctor;

use App\Services\PushService;
use App\Services\PushServiceDoctor;

use DB;

class RewardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.rewards.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.rewards.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RewardStoreRequest $request)
    {
        $docs = Doctor::all();
        $sponsorships = Sponsorship::find($request->sponsorships);

        DB::beginTransaction();
            $item = Reward::store($request);
            $item->sponsorships()->attach($request->sponsorships);
        DB::commit();

        $push = new PushServiceDoctor('New reward', $item->name.' is already been added check it now!');
        $push->pushToMany($docs);

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
        $item = Reward::withTrashed()->findOrFail($id);
        
        return view('admin.rewards.show', [
            'item' => $item,
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
    public function update(RewardStoreRequest $request, $id)
    {
        $item = Reward::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->name}";

        DB::beginTransaction();
            $item = Reward::store($request, $item);
            $item->sponsorships()->sync($request->sponsorships);
        DB::commit();
        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reward  $Rewards
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = Reward::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived Reward {$item->name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Reward  $Reward
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = Reward::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored Reward {$item->name}",
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
