<?php

namespace App\Http\Controllers\Admin\Calls;

use App\Models\Calls\Call;
use App\Models\Users\Doctor;
use App\Models\Users\MedicalRepresentative;

use App\Http\Controllers\FetchController;

use DB;

class CallFetchController extends FetchController
{

    public function setObjectClass()
	{
		$this->class = new Call;
	}

	/**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {
        if ($this->request->filled('status')) {
            $query = $query->where('status', $this->request->input('status'));
        }

        if ($this->request->filled('medrep')) {
            $query = $query->where('medical_representative_id', $this->request->input('medrep'));
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
            'status' => $item->renderStatus(),
            'approveUrl' => $item->renderApproveUrl(),
            'rejectUrl' => $item->renderRejectUrl(),
            'scheduled_date' => $item->scheduled_date . ' ' . $item->scheduled_time,
            'medRep' => $item->getMedRep(),
            'doctor' => $item->getDoctor(),
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
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
        $call = null;

        if ($id) {
        	$call = Call::withTrashed()->findOrFail($id);
            $call->status = $call->renderStatus();
            $call->archiveUrl = $call->renderArchiveUrl();
            $call->restoreUrl = $call->renderRestoreUrl();
        }
        $doctors = Doctor::get()->all();
        $medReps = MedicalRepresentative::select('id', 'fullname')->get()->all();            

    	return response()->json(compact('call', 'doctors', 'medReps'));
    }

}
