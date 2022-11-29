<?php

namespace App\Http\Controllers\Samples;

use App\Http\Controllers\FetchController;

use App\Models\Samples\SampleItem;
use App\Models\Samples\SampleItemImage;

class SampleItemFetchController extends FetchController
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new SampleItem;
    }

    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {
        /**
         * Queries
         * 
         */
        if ($this->request->filled('status')) {
            $query = $query->where('status', $this->request->input('status'));
        }

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
            $data = array_merge($data, [
                'id' => $item->id,
                'name' => $item->renderName(),
                'created_at' => $item->renderDate(),
                'status' => $item->renderStatus(),
                'status_class' => $item->renderStatus('class'),
                'canApprove' => $item->canApprove(),
                'canDeny' => $item->canDeny(),
                'deleted_at' => $item->deleted_at,
            ]);

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
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
            'approveUrl' => $item->renderApproveUrl(),
            'denyUrl' => $item->renderDenyUrl(),
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

    public function fetchView($id = null)
    {
        $item = null;
        $images = [];

        if ($id) {
            $item = SampleItem::withTrashed()->findOrFail($id);
            $images = SampleItemImage::formatToArray($item->images);
            $item->path = $item->renderImagePath();
            $item->status_label = $item->renderStatus();
            $item->status_class = $item->renderStatus('class');
            $item->canApprove = $item->canApprove();
            $item->canDeny = $item->canDeny();

            $item = $this->formatView($item);
        }

        $items = SampleItem::all();
        $statuses = SampleItem::getStatus();

        return response()->json([
            'item' => $item,
            'items' => $items,
            'images' => $images,
            'statuses' => $statuses,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->removeImageUrl = $item->renderRemoveImageUrl();
        $item->approveUrl = $item->renderApproveUrl();
        $item->denyUrl = $item->renderDenyUrl();

        return $item;
    }
}
