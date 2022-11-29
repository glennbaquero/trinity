<?php

namespace App\Http\Controllers\API\Care\Doctors;

use Illuminate\Http\Request;

use App\Http\Controllers\FetchController;

use App\Models\Users\Doctor;

class DoctorFetchController extends FetchController
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Set object class of fetched data
     * 
     * @return void
     */
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
        /**
         * Queries
         * 
         */
        $query = $this->request->user();
        return $query;
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
            'specialization' => $item->getSpecialization(),
            'created_at' => $item->renderCreatedAt(),
        ];
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
        $items = $this->request->user()->doctors;
        $hiddenColumn = ['pivot','qr_code_path','qr_id','full_qr','updated_at','deleted_at','created_at'];

        foreach($items as $item) {

            $item->ratings = $item->computeRatings();
            $item->makeHidden($hiddenColumn)->toArray();

            array_push($result, [
                'doctor' => $item,
                'qr_id' => $item->qr_id,
                'qr_code_path' => url($item->renderImagePath('qr_code_path')),
                'specialization' => $item->specialization->name
            ]); 
        }

        return $result;
    }
}
