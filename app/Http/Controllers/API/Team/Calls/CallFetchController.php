<?php

namespace App\Http\Controllers\API\Team\Calls;

use App\Models\Users\MedicalRepresentative;

use App\Http\Controllers\FetchController;
use Illuminate\Http\Request;

class CallFetchController extends FetchController
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

        foreach ($items as $item) {
            $result[$item->id] = $item->getCalls();
        }

        return $result;
    }

}
