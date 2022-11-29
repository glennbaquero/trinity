<?php

namespace App\Http\Controllers\Web\ActivityLogs;

use App\Http\Controllers\ActivityLogs\ActivityLogFetchController as FetchController;

class ActivityLogFetchController extends FetchController
{
    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function additionalQuery($query)
    {
        /**
         * Queries
         * 
         */
        $user = $this->request->user();

        $filters = [
            'causer_type' => 'App\Models\Users\User',
            'causer_id' => $user->id,
        ];

        $query = $query->where($filters);

        if ($this->request->filled('profile')) {
            $filters = [
                'subject_type' => 'App\Models\Users\Admin',
                'subject_id' => $this->request->user()->id,
            ];

            $query = $query->where($filters);
        }

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
            'showUrl' => $item->renderShowUrl('web'),
        ];
    }
}
