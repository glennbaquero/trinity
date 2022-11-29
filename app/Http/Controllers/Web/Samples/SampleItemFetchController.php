<?php

namespace App\Http\Controllers\Web\Samples;

use App\Http\Controllers\Samples\SampleItemFetchController as FetchController;

use App\Models\Samples\SampleItem;
use App\Models\Samples\SampleItemImage;

class SampleItemFetchController extends FetchController
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
            'showUrl' => $item->renderShowUrl('web'),
            'archiveUrl' => $item->renderArchiveUrl('web'),
            'restoreUrl' => $item->renderRestoreUrl('web'),
            'approveUrl' => $item->renderApproveUrl('web'),
            'denyUrl' => $item->renderDenyUrl('web'),
        ];
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl('web');
        $item->restoreUrl = $item->renderRestoreUrl('web');
        $item->removeImageUrl = $item->renderRemoveImageUrl('web');
        $item->approveUrl = $item->renderApproveUrl('web');
        $item->denyUrl = $item->renderDenyUrl('web');

        return $item;
    }
}
