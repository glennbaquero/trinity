<?php

namespace App\Http\Controllers\API\Doctor\Schedules;

use App\Http\Controllers\FetchController;
use Illuminate\Http\Request;

use App\Models\Schedules\Schedule;
use App\Models\Consultations\Consultation;

class ScheduleFetchController extends FetchController
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
        $this->class = new Schedule;
    }

    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */

    public function filterQuery($query)
    {
        $user = request()->user();
        
        if($this->request->date) {
            $query = $query->whereDate('date', $this->request->date)->where('doctor_id', $user->id)->orderBy('id', 'asc');            
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
            'date' => $item->date,
            'start_time' => $item->start_time,
            'end_time' => $item->end_time,
            'type' => $item->type,
            'status' => $item->status ? 'red': null,
        ];
    }
}
