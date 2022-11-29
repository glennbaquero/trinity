<?php

namespace App\Http\Controllers\Admin\RequestClaimReferrals;

use App\Http\Controllers\FetchController;

use App\Models\Referrals\RequestClaimReferral;
use App\Models\Vouchers\Voucher;

class RequestClaimReferralFetchController extends FetchController
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new RequestClaimReferral;
    }


    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {

    	if($this->request->filled('pending')) {
    		$query = $query->where(['distribute_by' => null, 'disapproved_by' => null]);
    	}

    	if($this->request->filled('approved')) {
			$query = $query->where('distribute_by', '!=', null);
    	}

    	if($this->request->filled('rejected')) {
			$query = $query->where('disapproved_by', '!=', null);    		
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
            'name' => $item->requester->renderFullName(),
            'status' => $item->renderStatus(),

            'reason' => $item->renderReason(),
            'disapproved_by' => $item->renderDisapprover(),

            'distribute_by' => $item->renderDistributor(),
            'claimed_at' => $item->renderClaimedAt(),

            'deleted_at' => $item->deleted_at,
            'created_at' => $item->renderCreatedAt(),
            
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),

            'approveUrl' => $item->renderApproveUrl(),
            'rejectUrl' => $item->renderRejectUrl(),      
        ];
    }

	public function fetchView($id = null) 
	{
        $item = null;
        $item = RequestClaimReferral::find($id);
        $vouchers = Voucher::get();

    	return response()->json([
    		'item' => $item,
            'vouchers' => $vouchers,
    	]);
    }


}
