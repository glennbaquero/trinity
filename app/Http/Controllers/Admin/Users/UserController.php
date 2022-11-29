<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Users\UserStoreRequest;

use App\Notifications\Web\Auth\VerifyEmail;
use App\Notifications\Admin\Auth\UserCreatedNotification;
use App\Notifications\Admin\CreditsUpdate\CreditsUpdateNotification;

use App\Models\StatusTypes\StatusType;
use App\Models\Users\User;

use Carbon\Carbon;

class UserController extends Controller
{

    public function get()
    {
        $patientsCount = User::count();
        return response()->json(compact('patientsCount'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users.index', [

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $item = User::store($request, null, [], true);

        if($request->doctors) {
            $item->doctors()->sync($request->doctors);
        }

        $item->notify(new UserCreatedNotification($request->password));

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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = User::withTrashed()->findOrFail($id);
        $statuses = StatusType::orderBy('order', 'asc')->get()
                    ->map(function($status) {
                        return $status->only(['id', 'name']);
                    });

        return view('admin.users.show', [
            'item' => $item,
            'statuses' => $statuses
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserStoreRequest $request, $id)
    {
        $item = User::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->renderName()}";

        $item = User::store($request, $item);

        if($request->doctors) {
            $item->doctors()->sync($request->doctors);
        } else {
            $item->doctors()->detach();
        }
  
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
        $item = User::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->renderName()}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Admin  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = User::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->renderName()}",
        ]);
    }

    /**
    * Approve a registered user's prescription image
    *
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function approve($id)
    {
        $user = User::where('id', $id);

        $user->update(['approved' => 1]);
        $user->first()->notify(new VerifyEmail(
            'Your account has been approved! You can now log in on Trinity Care App.'
        ));
        
        return response()->json([
            'message' => "User #{$id} successfully approved!",
            'redirectUrl' => route('admin.users.index')
        ]);
    }

    /**
    * Deny a registered user's prescription image
    *
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function deny($id)
    {
        $user = User::where('id', $id);

        $user->update(['approved' => 0]);
        $user->first()->notify(new VerifyEmail(
            'Sorry, your account has been reject due to the verification image that was not accepted.'
        ));
        
        return response()->json([
            'message' => "User #{$id} successfully rejected!",
            'redirectUrl' => route('admin.users.index')
        ]);
    }


    /**
     * Manage credits form
     * 
     * @param  int $id
     */
    public function manageCreditsForm($id)
    {
        $item = User::find($id);

        return view('admin.manage-credits.show', [
            'item' => $item,
            'submitUrl' => route('admin.users.update-credits', $item->id)
        ]);
    }

    /**
     * Manage credits form
     * 
     * @param  int $id
     */
    public function updateCredits(Request $request, $id)
    {
        $item = User::find($id);
        
        if($request->type == 1) {
            $item->processCredit($request->value);
        } else {
            $item->processCredit('-' . $request->value);
        }

        $item->notify(new CreditsUpdateNotification($request->message));

        return response()->json([
            'message' => "User credits has been updated",
        ]);

    }

}
