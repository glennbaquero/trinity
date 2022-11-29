<?php

namespace App\Models\Samples;

use App\Extendables\BaseModel as Model;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Notification;

use App\Traits\ManyImagesTrait;
use App\Traits\FileTrait;

use App\Helpers\StringHelpers;

use App\Notifications\Samples\SampleItemApproved;
use App\Notifications\Samples\SampleItemDenied;

use App\Models\Permissions\Permission;
use App\Models\Users\Admin;

class SampleItem extends Model
{
    use ManyImagesTrait, FileTrait;
    
    const STATUS_PENDING = 'PENDING';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_DENIED = 'DENIED';

    protected static $logAttributes = ['name', 'description', 'date', 'dates', 'data'];
    protected static $ignoreChangedAttributes = ['updated_at', 'status', 'reason'];

    public function images() {
        return $this->hasMany(SampleItemImage::class, 'sample_item_id');
    }

    protected $casts = [
        'data' => 'array',
        'dates' => 'array',
        'date' => 'date',
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    /**
     * @Getters
     */
    public static function getStatus() {
    	return [
    		['value' => static::STATUS_PENDING, 'label' => 'Pending', 'class' => 'bg-info'],
    		['value' => static::STATUS_APPROVED, 'label' => 'Approved', 'class' => 'bg-success'],
            ['value' => static::STATUS_DENIED, 'label' => 'Denied', 'class' => 'bg-danger'],
    	];
    }

    /**
     * @Setters
     */
    public static function store($request, $item = null, $columns = ['name', 'description', 'sample_item_id', 'data', 'date', 'dates', 'status'])
    {
        $vars = $request->only($columns);

        $vars['image_path'] = static::storeImage($request, $item, 'image_path', 'sample-item-images');

        if (!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        if ($request->hasFile('images')) {
            $item->addImages($request->file('images'));
        }

        return $item;
    }

    public function approve($request) {
        if ($this->canApprove()) {
            $this->status = static::STATUS_APPROVED;
            $this->save();

            $this->sendApproveNotifications();

            activity()
                ->causedBy($request->user())
                ->performedOn($this)
                ->log("{$this->renderLogName()} status has been changed to approved.");
        } else {
            throw ValidationException::withMessages([
                'status' => 'Status can no longer be change to deny.',
            ]);
        }

        return true;
    }

    public function deny($request) {
        if ($this->canDeny()) {
            $this->status = static::STATUS_DENIED;
            $this->reason = $request->input('reason');
            $this->save();

            $this->sendDenyNotifications();

            activity()
                ->causedBy($request->user())
                ->performedOn($this)
                ->log("{$this->renderLogName()} status has been changed to denied.");
        } else {
            throw ValidationException::withMessages([
                'status' => 'Status can no longer be change to deny.',
            ]);
        }

        return true;
    }

    /**
     * @Notifications
     */
    public function sendApproveNotifications() {
        $ids = Permission::getUsersByPermission(['admin.sample-items.crud']);
        $admins = Admin::whereIn('id', $ids)->get();

        if (count($admins)) {
            Notification::send($admins, new SampleItemApproved($this));
        }
    }

    public function sendDenyNotifications() {
        $ids = Permission::getUsersByPermission(['admin.sample-items.crud']);
        $admins = Admin::whereIn('id', $ids)->get();

        if (count($admins)) {
            Notification::send($admins, new SampleItemDenied($this));
        }
    }

    /**
     * @Checkers
     */
    public function canApprove() {
        if ($this->trashed()) {
            return false;
        }

        switch ($this->status) {
            case static::STATUS_PENDING:
                return true;
            case static::STATUS_APPROVED:
            case static::STATUS_DENIED:
                return false;
        }
    }

    /**
     * @Checkers
     */
    public function canDeny() {
        if ($this->trashed()) {
            return false;
        }
        
        switch ($this->status) {
            case static::STATUS_PENDING:
                return true;
            case static::STATUS_APPROVED:
            case static::STATUS_DENIED:
                return false;
        }
    }

    /**
     * @Render
     */
    public function renderStatus($column = 'label') {
    	return static::renderConstants(static::getStatus(), $this->status, $column);
    }

    public function renderName() {
        return $this->name;
    }

    public function renderApproveUrl($prefix = 'admin') {
        return route(StringHelpers::addRoutePrefix($prefix) . 'sample-items.approve', $this->id);
    }

    public function renderDenyUrl($prefix = 'admin') {
        return route(StringHelpers::addRoutePrefix($prefix) . 'sample-items.deny', $this->id);
    }

    public function renderShowUrl($prefix = 'admin') {
        return route(StringHelpers::addRoutePrefix($prefix) . 'sample-items.show', $this->id);
    }

    public function renderArchiveUrl($prefix = 'admin') {
        return route(StringHelpers::addRoutePrefix($prefix) . 'sample-items.archive', $this->id);
    }

    public function renderRestoreUrl($prefix = 'admin') {
        return route(StringHelpers::addRoutePrefix($prefix) . 'sample-items.restore', $this->id);
    }

    public function renderRemoveImageUrl($prefix = 'admin') {
        return route(StringHelpers::addRoutePrefix($prefix) . 'sample-items.remove-image', $this->id);
    }
}
