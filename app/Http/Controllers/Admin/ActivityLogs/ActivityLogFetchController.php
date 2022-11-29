<?php

namespace App\Http\Controllers\Admin\ActivityLogs;

use App\Http\Controllers\ActivityLogs\ActivityLogFetchController as FetchController;

class ActivityLogFetchController extends FetchController
{
    public function additionalQuery($query) {
    	if ($this->request->filled('profile')) {
            $filters = [
                'subject_type' => 'App\Models\Users\Admin',
                'subject_id' => $this->request->user()->id,
            ];

            $query = $query->where($filters);
        }

        return $query;
    }
}
