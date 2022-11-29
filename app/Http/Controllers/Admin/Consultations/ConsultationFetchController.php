<?php

namespace App\Http\Controllers\Admin\Consultations;

use App\Http\Controllers\FetchController;

use App\Models\Consultations\Consultation;
use App\Models\Users\Doctor;
use App\Models\Users\User;

class ConsultationFetchController extends FetchController
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Consultation;
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
        if($this->request->filled('doctor') && $this->request->filled('id')) {
            $query = $query->where('doctor_id', $this->request->id);
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
            $data = array_merge($data, [
                'id' => $item->id,
                'consultation_number' => $item->consultation_number,
                'doctor' => $item->doctor->renderName(),
                'user' => $item->user->renderName(),
                'fee' => $item->consultation_fee,
                'schedule_date' => $item->renderScheduleDate(),
                'status' => $item->renderStatus(),
                'created_at' => $item->renderDate(),
                'deleted_at' => $item->deleted_at,
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
        $doctors = Doctor::all();
        $doctors->prepend(['id' => 0.1, 'fullname' => 'All']);
        $users = User::all();

        return response()->json([
            'doctors' => $doctors,
            'users' => $users,
        ]);

    }
}
