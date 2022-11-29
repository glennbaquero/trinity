<?php

namespace App\Http\Controllers\Web\Notifications;

use App\Http\Controllers\Notifications\NotificationFetchController as FetchController;

use Auth;

class NotificationFetchController extends FetchController
{
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
        $user = Auth::user();

        $filters = [
            'notifiable_type' => get_class($user),
            'notifiable_id' => $user->id,
        ];

        $query = $query->where($filters);

        if ($this->request->filled('read')) {
            $query = $query->whereNotNull('read_at');
        } else {
            $query = $query->whereNull('read_at');
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
            'id' => $item->id,
            'title' => $item->renderDataColumn('title'),
            'message' => $item->renderDataColumn('message'),
            'created_at' => $item->renderDate(),
            'read_at' => $item->renderDate('read_at'),
            'showUrl' => $item->renderShowUrl('web'),
            'readUrl' => $item->renderReadUrl('web'),
            'unreadUrl' => $item->renderUnreadUrl('web'),
        ];
    }
}
