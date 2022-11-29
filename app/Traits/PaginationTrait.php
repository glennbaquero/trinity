<?php

namespace App\Traits;

use App\Helpers\AuthHelpers;

trait PaginationTrait
{
	public static function generatePagePaginationUrls($request, $currentItemId) {

		$nextPageUrl = null;
		$prevPageUrl = null;

		$nextItem = (new static)->newQuery();
		$prevItem = (new static)->newQuery();

		$currentItem = static::withTrashed()->find($currentItemId);

		if ($currentItem->trashed()) {
			$nextItem = $nextItem->onlyTrashed();
			$prevItem = $prevItem->onlyTrashed();
		}

		$nextItem = $nextItem->where('id', '>', $currentItemId)->orderBy('id', 'asc')->first();
		$prevItem = $prevItem->where('id', '<', $currentItemId)->orderBy('id', 'desc')->first();

		$guard = AuthHelpers::getGuard($request);

		if ($nextItem && method_exists($nextItem, 'renderShowUrl')) {
			$nextPageUrl = $nextItem->renderShowUrl($guard);
		}

		if ($prevItem && method_exists($prevItem, 'renderShowUrl')) {
			$prevPageUrl = $prevItem->renderShowUrl($guard);
		}

		return [
			'next_page' => $nextPageUrl,
			'prev_page' => $prevPageUrl,
		];
	}
}