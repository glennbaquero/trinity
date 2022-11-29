<?php

namespace App\Http\Controllers\ActivityLogs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ActivityLogs\ActivityLog;

class ActivityLogController extends Controller
{
    protected $indexView;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filterTypes = json_encode(ActivityLog::getTypes());

        return view($this->indexView, [
            'filterTypes' => $filterTypes,
        ]);
    }

    public function getMostRecent()
    {
        $activities = ActivityLog::latest()->take(5)->get();
        $recent = $activities->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->renderName(),
                'caused_by' => $item->renderCauserName(),
                'show_causer' => $item->renderCauserShowUrl(),
                'subject_type' => $item->renderSubjectType(),
                'subject_name' => $item->renderSubjectName(),
                'date' => $item->renderStandardDate(),
                'time' => $item->renderStandardTime()
            ];
        });

        return response()->json(compact('recent'));
    }
}
