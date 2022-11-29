<?php

namespace App\Http\Controllers\Admin\Inventories;

use App\Http\Controllers\FetchController;

use App\Models\Inventories\Inventory;

class InventoryFetchController extends FetchController
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Inventory;
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
                'sku' => $item->product->sku,
                'product_name' => $item->product->name,
                'stocks' => $item->stocks,
                'stocks_status' => $item->renderStatus(),
                'created_at' => $item->renderCreatedAt(),
                'updated_at' => $item->renderCreatedAt('updated_at'),
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
        ];
    }


    public function fetchView($id = null)
    {

        $item = null;

        if ($id) {
            $item = Inventory::find($id);
            $item->remaining_stocks = $item->stocks;
            $product = $item->product;
        }

        return response()->json([
            'item' => $item,
            'product' => $product
        ]);

    }

}
