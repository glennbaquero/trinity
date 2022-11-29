<?php

namespace App\Http\Controllers\Admin\Permissions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Permissions\PermissionCategory;
use App\Models\Roles\Role;

class PermissionFetchController extends Controller
{
    public function fetch(Request $request, $id)
    {
        $categories = PermissionCategory::with('permissions')->orderBy('name', 'asc')->get();
        $role = Role::withTrashed()->findOrFail($id);
        $permission_ids = $role->permissions()->pluck('id')->toArray();

        return response()->json([
            'categories' => $categories,
            'permission_ids' => $permission_ids,
        ]);
    }
}
