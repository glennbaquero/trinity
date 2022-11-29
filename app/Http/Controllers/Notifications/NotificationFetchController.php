<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\FetchController;

use Auth;
use App\Models\Notifications\Notification;

class NotificationFetchController extends FetchController
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Notification;
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
        
        $this->total = 10;

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
     * Custom formatting of data
     * 
     * @param Illuminate\Support\Collection $items
     * @return array $result
     */
    public function formatData($items)
    {
        $result = [];
        
        // $items = $items->paginate(10); 

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
            'title' => $item->renderDataColumn('title'),
            'message' => $item->renderDataColumn('message'),
            'created_at' => $item->renderDate(),
            'read_at' => $item->renderDate('read_at'),
            'showUrl' => $item->renderShowUrl(),
            'readUrl' => $item->renderReadUrl(),
            'unreadUrl' => $item->renderUnreadUrl(),
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
}
