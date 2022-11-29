<?php

namespace App\Http\Controllers\Admin\Doctors;

use App\Models\Users\Doctor;
use App\Models\Users\MedicalRepresentative;
use App\Models\Specializations\Specialization;

use App\Http\Controllers\FetchController;

class DoctorFetchController extends FetchController
{
    
	public function setObjectClass()
	{
		$this->class = new Doctor;
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
            'fullname' => $item->fullname,
            'ratings' => $item->computeRatings(),
            'status' => $item->renderStatus(),
            'approveUrl' => $item->renderApproveUrl(),
            'rejectUrl' => $item->renderRejectUrl(),
            'specialization' => $item->getSpecialization(),
            'qr_path' => $item->renderImagePath('qr_code_path'),
            'qr_id' => $item->qr_id,
            'credits' => $item->countCredits(),
            'points' => $item->renderPoints(),
            'manageCreditsUrl' => $item->renderManageCreditsUrl(),
            'medRep' => $item->getMedRep(),
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
        $doctor = null;

        if ($id) {
        	$doctor = Doctor::withTrashed()->findOrFail($id);
        	$doctor->fullname = $doctor->fullname;
            $doctor->qr_path = $doctor->renderImagePath('qr_code_path');
            $doctor->archiveUrl = $doctor->renderArchiveUrl();
            $doctor->restoreUrl = $doctor->renderRestoreUrl();
            $doctor->downloadQRUrl = $doctor->renderDownloadQRUrl();
            $doctor->resetPasswordUrl = $doctor->renderResetPassword();
        }

        $doctors = Doctor::all();
        $specializations = Specialization::select('id', 'name')->get()->all();
        $medReps = MedicalRepresentative::select('id', 'fullname')->get()->all();
        array_push($medReps, [
            'id' => 0,
            'fullname' => 'No link MD'
        ]);

    	return response()->json(compact('doctor', 'doctors', 'specializations', 'medReps'));
    }

}
