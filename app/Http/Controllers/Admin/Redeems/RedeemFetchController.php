<?php

namespace App\Http\Controllers\Admin\Redeems;

use App\Models\Rewards\Redeem;

use App\Http\Controllers\FetchController;

class RedeemFetchController extends FetchController
{
    
	public function setObjectClass()
	{
		$this->class = new Redeem;
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
            'doctor' => $item->doctor->fullname,
            'doctorShowUrl' => $item->doctor->renderShowUrl(),
            'reward' => $item->reward->name,
            'rewardShowUrl' => $item->reward->renderShowUrl(),
            'sponsorships' => $item->getSponsorships(),
            'points' => $item->used_points,
            'status' => $item->renderStatus(),
            'approveUrl' => $item->renderApproveUrl(),
            'rejectUrl' => $item->renderRejectUrl(),
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
