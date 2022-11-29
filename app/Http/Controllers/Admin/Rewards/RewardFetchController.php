<?php

namespace App\Http\Controllers\Admin\Rewards;

use App\Http\Controllers\FetchController;

use App\Models\Rewards\Reward;
use App\Models\Rewards\RewardCategory;
use App\Models\Rewards\Sponsorship;

class RewardFetchController extends FetchController
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Reward;
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
                'name' => $item->name,
                'category_name' => $item->category->name,
                'points' => $item->points,
                'created_at' => $item->renderDate(),
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
        ];
    }

    public function fetchView($id = null)
    {

        $item = null;
        $images = [];

        if ($id) {
            $item = Reward::withTrashed()->find($id);
            $item->sponsorship_id = $item->sponsorships()->allRelatedIds();
            $item->image_path = $item->renderImagePath();
            $item->archiveUrl = $item->renderArchiveUrl();
            $item->restoreUrl = $item->renderRestoreUrl();
        }

        $categories = RewardCategory::get();
        $sponsorships = Sponsorship::all();

        return response()->json([
            'item' => $item,
            'categories' => $categories,
            'sponsorships' => $sponsorships,
        ]);

    }
}
