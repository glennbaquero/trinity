<?php

namespace App\Http\Controllers\API\Care\Cities;

use App\Models\Cities\City;

use Illuminate\Http\Request;
use App\Http\Controllers\FetchController;

class CityFetchController extends FetchController
{
    
	/**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new City;
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
        $cities = collect(City::all())->map(function($city) {
        	return [
        		'province_id' => $city['province_id'],
        		'text' => trim($city['name']),
        		'value' => $city['id']
        	];
        });

        return $cities;
    }

}
