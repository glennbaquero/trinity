<?php

namespace App\Models\Notifications;

use Illuminate\Notifications\DatabaseNotification;

use Laravel\Scout\Searchable;
use App\Traits\HelperTrait;

use App\Helpers\StringHelpers;

use App\Models\Samples\SampleItem;

class Notification extends DatabaseNotification
{
	use HelperTrait, Searchable;

    protected $appends = ['short_title', 'short_description'];

	/**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
        ];
    }

    /**
     * @Renders
     */
    public function renderDataColumn($column, $emptyvalue = null) {
    	return isset($this->data[$column]) ? $this->data[$column] : $emptyvalue;
    }

    public function renderShowUrl($prefix = 'admin') {
    	$result = null;
    	$item = null;

        if ($class = $this->renderDataColumn('subject_type')) {
            $item = $class::withTrashed()->find($this->renderDataColumn('subject_id'));

            if ($item) {
                $result = $item->renderShowUrl($prefix);
            }
        }

        return $result;
    }

    public function renderDate($column = 'created_at') {
        $date = null;

        if (isset($this->$column) && $this->$column) {
            $date = $this->$column->diffForHumans();
        }

        return $date;
    }

    public function renderReadUrl($prefix = 'admin') {
        return route(StringHelpers::addRoutePrefix($prefix) . 'notifications.read', $this->id);
    }

    public function renderUnreadUrl($prefix = 'admin') {
        return route(StringHelpers::addRoutePrefix($prefix) . 'notifications.unread', $this->id);
    }

    /**
     * Appends short description
     *
     * @return string
     */
    public function getShortDescAttribute()
    {
        return str_limit($this->message, 150);
    }

    /**
     * Appends short description
     *
     * @return string
     */
    public function getShortDescriptionAttribute()
    {
        return str_limit($this->message, 150);
    }

    /**
     * Appends short title
     *
     * @return string
     */
    public function getShortTitleAttribute()
    {
        return str_limit($this->title, 150);
    }
}