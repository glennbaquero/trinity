<?php

namespace App\Http\Controllers\Admin\Payouts;

use App\Http\Controllers\FetchController;

use App\Models\Payouts\Payout;
use App\Models\Users\Doctor;

class PayoutFetchController extends FetchController
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Payout;
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
            'doctor' => $item->doctor->renderName(),
            'value' => $item->value,
            'created_at' => $item->renderDate(),
            'deleted_at' => $item->deleted_at,
            'canArchive' => $item->canArchive(),
            'approveUrl' => $item->renderApproveUrl(),
            // 'disapproveUrl' => $item->renderDisapproveUrl(),
            'disapprovalFormUrl' => $item->renderDisapprovalFormUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;

        if ($id) {
            $item = Payout::withTrashed()->find($id);
            $item->value = $item->value;
            $item->doctor_id = $item->doctor->renderName();
        }

        return response()->json([
            'item' => $item,
        ]);

    }
}
