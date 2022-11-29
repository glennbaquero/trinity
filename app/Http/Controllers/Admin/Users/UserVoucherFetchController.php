<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\FetchController;

use App\Models\Vouchers\UserVoucher;


class UserVoucherFetchController extends FetchController
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new UserVoucher;
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

        if($this->request->filled('voucher') && $this->request->filled('id')) {
        	$query = $query->where('user_id', $this->request->input('id'));
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
            'name' => $item->renderName(),
            'code' => $item->code,
            'type' => $item->renderType(),
            'discount' => $item->discount,
            'created_at' => $item->created_at->format('M d, Y (g:m A)'),
        ];
    }

}
