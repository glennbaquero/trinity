<?php

namespace App\Http\Controllers\Admin\Vouchers;

use App\Http\Controllers\FetchController;

use App\Models\Vouchers\Voucher;

class VoucherFetchController extends FetchController
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Voucher;
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
            'name' => $item->renderName(),
            'type' => $item->renderType(),
            'voucher_type' => $item->renderVoucherType(),
            'user_type' => $item->renderUserType(),
            'discount' => $item->discount,
            'created_at' => $item->renderDate(),
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
            'deleted_at' => $item->deleted_at,
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
        $page_items = [];
        $types = Voucher::renderTypes();
        $voucher_types = Voucher::renderVoucherTypes();        
        $user_types = Voucher::renderUserTypes();
        
        if ($id) {
        	$item = Voucher::withTrashed()->findOrFail($id);
            $item->archiveUrl = $item->renderArchiveUrl();
            $item->restoreUrl = $item->renderRestoreUrl();
          
        }

    	return response()->json([
    		'item' => $item,
            'types' => $types,
            'user_types' => $user_types,
            'voucher_types' => $voucher_types
    	]);
    }
}
