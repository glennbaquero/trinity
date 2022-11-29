<?php

namespace App\Models\Permissions;

use Illuminate\Database\Eloquent\Model;

class PermissionCategory extends Model
{
    protected $guarded = [];

    public function permissions() {
    	return $this->hasMany(Permission::class, 'category_id');
    }
}