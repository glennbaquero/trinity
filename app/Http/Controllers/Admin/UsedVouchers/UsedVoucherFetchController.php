<?php

namespace App\Http\Controllers\Admin\UsedVouchers;

use App\Http\Controllers\FetchController;

use App\Models\Vouchers\UsedVoucher;

class UsedVoucherFetchController extends FetchController
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new UsedVoucher;
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
            'name' => $item->renderVoucherName(),
            'type' => $item->renderVoucherType(),
            'discount' => $item->total_discount,
            'invoice' => $item->invoice->invoice_number,
            'invoice_show_url' => $item->invoice->renderShowUrl(),
            'created_at' => $item->created_at->format('M d, Y (g:m A)'),
        ];
    }
}
