<?php

namespace App\Http\Controllers\Admin\Prescriptions;

use App\Models\Prescriptions\Prescription;
use App\Http\Controllers\FetchController;

class PrescriptionFetchController extends FetchController
{
    
	public function setObjectClass()
	{
		$this->class = new Prescription;
	}

	/**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {
        if ($this->request->filled('approved')) {
            $status = $this->request->input('approved') == 2 ? null : $this->request->input('approved');
            $query = $query->where('approved', $status);
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

        foreach ($items as $item) {
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
            'user' => "{$item->user->first_name} {$item->user->last_name}",
            'product' => $item->parent ? $item->parent->name : 'N/A',
            'image_path' => url($item->renderImagePath()),
            'approved' => $item->approved,
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'deleted_at' => $item->deleted_at,
            'created_at' => $item->renderCreatedAt(),
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

        if ($id) {
        	$item = Prescription::withTrashed()->findOrFail($id);
        	$item->user = "{$item->user->first_name} {$item->user->last_name}";
        	$item->product = $item->product->name;
        	$item->image_path = $item->image_path;
        	$item->approved = (bool) $item->approved;
            $item->archiveUrl = $item->renderArchiveUrl();
        }

    	return response()->json(compact('item'));
    }

}
