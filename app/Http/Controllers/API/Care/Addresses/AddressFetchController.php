<?php

namespace App\Http\Controllers\API\Care\Addresses;

use App\Models\Users\User;
use App\Http\Controllers\FetchController;

class AddressFetchController extends FetchController
{
    
	/**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new User;
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
        $query = auth()->user();
        return $query;
    }

    /**
     * Custom formatting of data
     * 
     * @param Illuminate\Support\Collection $items
     * @return array $cubrid_result(result, row)
     */
    public function formatData($items)
    {
        $userAddresses = $this->request->user()->addresses()->orderBy('created_at', 'desc')->get();
        $addresses = $userAddresses->map(function($address) {
            return $address->getUserAddresses();
        });

        return $addresses->all();
    }

}
