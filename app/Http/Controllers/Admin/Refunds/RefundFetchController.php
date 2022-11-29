<?php

namespace App\Http\Controllers\Admin\Refunds;

use App\Http\Controllers\FetchController;

use App\Models\Refunds\Refund;
use App\Models\Users\Doctor;
use App\Models\Users\User;
use App\Models\ConsultationChats\ConsultationChat;

class RefundFetchController extends FetchController
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Refund;
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
            'user' => $item->user->renderName(),
            'doctor' => $item->doctor->renderName(),
            'consultation_fee' => $item->consultation->consultation_fee,
            'reason' => $item->reason,
            'created_at' => $item->renderDate(),
            'deleted_at' => $item->deleted_at,
            'canArchive' => $item->canArchive(),
            'showUrl' => $item->renderShowUrl(),
            'approveUrl' => $item->renderApproveUrl(),
            'disapprovalFormUrl' => $item->renderDisapprovalFormUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;

        if ($id) {
            $item = Refund::withTrashed()->find($id);
            $item->reason = $item->reason;
            $item->user_name = $item->user->renderName();
            $item->doctor_name = $item->doctor->renderName();
            $item->consultation_fee = $item->consultation->consultation_fee;
            $item->scheduled_date = $item->scheduled_date ? $item->schedule->date->format('M d, Y (g:m A)') : 'N/A';
            $item->approveUrl = $item->renderApproveUrl();
            $item->disapprovalFormUrl = $item->renderDisapprovalFormUrl();
            $item->canArchive = $item->canArchive();

            $chats = ConsultationChat::filter($item->consultation->id, true);
        }

        return response()->json([
            'item' => $item,
            'chats' => $chats,
        ]);

    }
}
