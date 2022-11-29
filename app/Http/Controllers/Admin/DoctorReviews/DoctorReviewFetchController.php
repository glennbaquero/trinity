<?php

namespace App\Http\Controllers\Admin\DoctorReviews;

use App\Http\Controllers\FetchController;

use App\Models\Reviews\DoctorReview;

class DoctorReviewFetchController extends FetchController
{

    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new DoctorReview;
    }

    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {
        if($this->request->filled('doctor')) {
            $query = $query->where('doctor_id', $this->request->input('doctor'));
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
            'consultation_number' => $item->consultation->consultation_number,
            'reviewer' => $item->reviewer->renderFullName(),
            'doctor' => $item->doctor->renderName(),
            'ratings' => $item->ratings,
            'description' => $item->renderDescription(),
            'deleted_at' => $item->deleted_at,
            'created_at' => $item->renderCreatedAt(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),            
        ];
    }

    protected function sortQuery($query) 
    {

        switch ($this->sort) {
            default:
                    $query = $query->orderBy($this->sort, $this->order);
                break;
        }

        return $query;
    }

}
