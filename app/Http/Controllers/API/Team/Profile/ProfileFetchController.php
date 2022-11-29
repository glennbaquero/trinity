<?php

namespace App\Http\Controllers\API\Team\Profile;

use App\Models\Users\MedicalRepresentative;

use Illuminate\Http\Request;
use App\Http\Controllers\FetchController;

class ProfileFetchController extends FetchController
{
    
	public function setObjectClass()
	{
		$this->class = new MedicalRepresentative;
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
     * @return array $result
     */
    public function formatData($items)
    {
        $result = [];

        foreach($items as $item) {
            array_push($result, [
                'profile' => $item->getProfile()
            ]);
        }

        return $result;
    }

}
