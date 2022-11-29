<?php

namespace App\Http\Controllers\API\Care\Regions;

use App\Models\Regions\Region;

use Illuminate\Http\Request;
use App\Http\Controllers\FetchController;

class RegionFetchController extends FetchController
{
    
	/**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Region;
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
        $regions = collect(Region::all())->map(function($region) {
        	return [
        		'text' => $region['name'],
        		'value' => $region['id']
        	];
        });

        return $regions;
    }

}
