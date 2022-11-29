<?php

namespace App\Models\Announcements;

use App\Extendables\BaseModel as Model;

class AnnouncementType extends Model
{
	/**
	 * Relationship 
	 */
	
	public function announcements() {
		return $this->hasMany(Announcement::class);
	}
	
    /**
	 * @Getters
	 */
	public static function store($request, $item = null, $columns = ['name'])
    {

        $vars = $request->only($columns);

        if (!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }
        
        return $item;
    }

	public function renderArchiveUrl() {
	    return route('admin.announcement-types.archive', $this->id);
	}

	public function renderRestoreUrl() {
	    return route('admin.announcement-types.restore', $this->id);
	}

	public function renderShowUrl() {
	    return route('admin.announcement-types.show', $this->id);
	}
}
