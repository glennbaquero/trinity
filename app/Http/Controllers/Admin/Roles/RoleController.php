<?php

namespace App\Http\Controllers\Admin\Roles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Roles\RoleStoreRequest;
use App\Http\Requests\Admin\Permissions\UpdatePermissionRequest;
use App\Helpers\ArrayHelpers;

use App\Models\Roles\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('App\Http\Middleware\Admin\Roles\RoleMiddleware', 
            ['only' => ['index', 'create', 'store', 'show', 'update', 'archive', 'restore']]
        );
    }

    public function index()
    {
    	return view('admin.roles.index', [

        ]);
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(RoleStoreRequest $request)
    {
        $action = 1;

        $item = Role::store($request);
        $message = 'You have successfully created role #' . $item->id;

        return response()->json([
            'message' => $message,
            'action' => $action,
            'redirect' => $item->renderShowUrl(),
        ]);
    }

    public function show($id)
    {
        $item = Role::withTrashed()->findOrFail($id);

        return view('admin.roles.show', [
            'item' => $item,
        ]);
    }

    public function update(RoleStoreRequest $request, $id)
    {
        $item = Role::withTrashed()->findOrFail($id);
        $action = 1;
        $message = 'You have successfully updated role #' . $item->id;

        $item = Role::store($request, $item);

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
        $item = Role::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->renderName()}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Role  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = Role::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully archived {$item->renderName()}",
        ]);
    }

    public function updatePermissions(UpdatePermissionRequest $request, $id)
    {
        $item = Role::withTrashed()->findOrFail($id);

        $item->updatePermissions($request);

        $message = 'You have successfully updated the permissions of ' . $item->renderName() . '.';
        $action = 1;

        return response()->json([
            'message' => $message,
            'action' => $action,
        ]);
    }
}