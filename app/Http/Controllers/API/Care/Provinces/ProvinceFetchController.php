<?php

namespace App\Http\Controllers\API\Care\Provinces;

use App\Models\Provinces\Province;

use Illuminate\Http\Request;
use App\Http\Controllers\FetchController;

class ProvinceFetchController extends FetchController
{
    
	/**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Province;
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
        $provinces = collect(Province::all())->map(function($province) {
        	return [
        		'region_id' => $province['region_id'],
        		'text' => trim($province['name']),
        		'value' => $province['id']
        	];
        });

        return $provinces;
    }

}
