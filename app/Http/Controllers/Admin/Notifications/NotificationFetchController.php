<?php

namespace App\Http\Controllers\Admin\Notifications;

use App\Http\Controllers\Notifications\NotificationFetchController as FetchController;

class NotificationFetchController extends FetchController
{
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
}
