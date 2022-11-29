<?php

namespace App\Models\ActivityLogs;

use Spatie\Activitylog\Models\Activity;
use Laravel\Scout\Searchable;

use App\Helpers\ObjectHelpers;
use App\Helpers\ArrayHelpers;

use App\Traits\ArchiveableTrait;
use App\Traits\HelperTrait;

class ActivityLog extends Activity
{
    use ArchiveableTrait, HelperTrait, Searchable;

	/**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'subject_type' => $this->subject_type,
            'description' => $this->description,
        ];
    }

    /**
     * @Getters
     */
    public static function getTypes() {
        $types = static::pluck('subject_type')->unique();
        $result = [];

        foreach ($types as $type) {
            $result[] = [
                'value' => $type,
                'label' => ObjectHelpers::getShortClassName($type),
            ];
        }

        $result = ArrayHelpers::sortArray($result, 'label');

        return $result;
    }

    /**
     * @Renders
     */
    public function renderName() {
    	return $this->description;
    }

    public function renderShowUrl($prefix = 'admin') {
    	$result = null;

    	if ($this->subject && method_exists($this->subject, 'renderShowUrl')) {
    		$result = $this->subject->renderShowUrl($prefix);
    	}

    	return $result;
    }

    public function renderSubjectType() {
        $result = 'System';

        if ($this->subject_type) {
            $result = ObjectHelpers::getShortClassName($this->subject_type) . ' #' . $this->subject_id;
        }

        return $result;
    }

    public function renderSubjectName() {
        $result = null;

        if ($this->subject && method_exists($this->subject, 'renderName')) {
            $result = $this->subject->renderName();
        }

        return $result;
    }

    public function renderCauserName() {
        $result = 'System';

        if ($this->causer && method_exists($this->causer, 'renderName')) {
            $result = $this->causer->renderName();
        }

        return $result;
    }

    public function renderCauserShowUrl() {
        $result = null;

        if ($this->causer && method_exists($this->causer, 'renderShowUrl')) {
            $result = $this->causer->renderShowUrl();
        }

        return $result;
    }

    public function renderStandardDate()
    {
        return $this->created_at->format('M d, Y');
    }

    public function renderStandardTime()
    {
        return date("g:i A", strtotime($this->created_at->format('H:m')));
    }
}
