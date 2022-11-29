<?php

namespace App\Models\Permissions;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Traits\HasRoles;

class Permission extends SpatiePermission
{
	/**
	 * @Relationships
	 */

	public function category() {
		return $this->belongsTo(PermissionCategory::class, 'category_id');
	}

    /**
     * @Getters
     */
    public static function getUsersByPermission(array $permissions) {
        $ids = [];
        $permissions = Permission::whereIn('name', $permissions)->get();

        foreach ($permissions as $permission) {
            foreach ($permission->roles as $role) {
                $ids = array_merge($ids, $role->users()->pluck('id')->toArray());
            }
        }

        return array_unique($ids);
    }
}
