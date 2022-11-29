<?php

namespace App\Http\Controllers\Admin\Points;

use App\Models\Points\Point;

use App\Http\Controllers\FetchController;

class PointFetchController extends FetchController
{
    
	public function setObjectClass()
	{
		$this->class = new Point;
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
        // $points <= 0 ? $item->getUser($item->pointable_type,$item->pointable_id)->points->sum('points') : $item->points
        return [
            'id' => $item->id,
            'user' => $item->getUser($item->pointable_type,$item->pointable_id)->fullname,
            'type' => $item->getType($item->pointable_type),
            'amount' => $item->points <= 0 ? $item->getPoints($item->pointable_type,$item->pointable_id, $item->points) : $item->points,
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

}
