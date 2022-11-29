<?php

namespace App\Http\Controllers\Admin\MedRepTargets;

use App\Http\Controllers\FetchController;

use App\Models\MedRepTargets\MedRepTarget;

class MedRepTargetFetchController extends FetchController
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new MedRepTarget;
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
        if($this->request->filled('medrep') && $this->request->input('medrep')) {
            $query = $query->where('medical_representative_id', $this->request->input('medrep'));
        }
        
        $query = $query->where('year', date('Y'));

        $query = $query->withTrashed();
        
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
                'type' => $item->type,
                'display_type' => $item->renderType()['name'],
                'month' => $item->month,                
                'display_month' => $item->renderMonth()['name'],
                'year' => $item->year,                
                'display_year' => $item->year,
                'value' => $item->value,
                'display_value' => $item->renderFormattedValue(),
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
			'updateUrl' => $item->renderUpdateUrl(),	     
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }


}
