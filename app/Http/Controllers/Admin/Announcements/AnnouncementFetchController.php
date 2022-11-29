<?php

namespace App\Http\Controllers\Admin\Announcements;

use App\Http\Controllers\FetchController;

use App\Models\Announcements\Announcement;
use App\Models\Announcements\AnnouncementType;

class AnnouncementFetchController extends FetchController
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Announcement;
    }

    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {
        return $query;
    }

    /**
     * Custom formatting of data
     * 
     * @param Illuminate\Support\Collection $items
     * @return array $result
     */
    public function formatData($items)
    {
        $result = [];

        foreach($items as $item) {
            $data = $this->formatItem($item);
            array_push($result, $data);
        }

        return $result;
    }

    /**
     * Build array data
     * 
     * @param  App\Contracts\AvailablePosition
     * @return array
     */
    protected function formatItem($item)
    {
        return [
            'id' => $item->id,
            'app_label' => $item->renderAppCompatibilityLabel(),
            'app_class' => $item->renderAppCompatibilityClass(),
            'title' => $item->title,
            'type' => $item->announcementType->name,
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
            'deleted_at' => $item->deleted_at,
            'created_at' => $item->renderCreatedAt(),
        ];
    }

    protected function sortQuery($query) {

        switch ($this->sort) {
            default:
                    $query = $query->orderBy($this->sort, $this->order);
                break;
        }

        return $query;
    }

    public function fetchView($id = null) {
        $item = null;

        $apps = Announcement::getAppCompatible();
        $types = AnnouncementType::all();

        if ($id) {
        	$item = Announcement::withTrashed()->findOrFail($id);
            $item->archiveUrl = $item->renderArchiveUrl();
            $item->restoreUrl = $item->renderRestoreUrl();
            $item->path = $item->renderImagePath('image_path');
        }

    	return response()->json([
    		'item' => $item,
    		'apps' => $apps,
    		'types' => $types
    	]);
    }
}
