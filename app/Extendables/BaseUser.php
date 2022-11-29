<?php

namespace App\Extendables;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

use Spatie\Activitylog\Traits\LogsActivity;
use Laravel\Scout\Searchable;
use Hash;
use Auth;
use Carbon\Carbon;

use App\Traits\ArchiveableTrait;
use App\Traits\HelperTrait;
use App\Traits\FileTrait;
use App\Traits\PaginationTrait;

class BaseUser extends Authenticatable
{
    use Notifiable, Searchable, ArchiveableTrait, HelperTrait, FileTrait, LogsActivity, PaginationTrait;

    protected static $logAttributes = ['first_name', 'last_name', 'email', 'image_path'];
    protected static $logOnlyDirty = false;
    protected static $ignoreChangedAttributes = ['updated_at', 'remember_token', 'email_verified_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getDescriptionForEvent(string $eventName): string {
        return "Account has been {$eventName}";
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
        ];
    }

    /**
     * @Methods
     */
    public static function store($request, $item = null, $columns = [], $appUser = false) 
    {   
        if (!count($columns)) {
            $columns = ['first_name', 'last_name', 'email'];
        }

        $vars = $request->only($columns);

        $vars['image_path'] = static::storeImage($request, $item, 'image_path', 'admin-avatars');


        if($request->type) {
            $vars['type'] = $request->type;
        }

        if (!$item) {
            $vars['password'] = $request->password ? bcrypt($request->password) : uniqid();
            $vars['email_verified_at'] = Carbon::now();
            $item = static::create($vars);
            if(!$appUser) {
                $broker = $item->broker();
                $broker->sendResetLink($request->only('email'));                
            }
        } else {
            $vars['email_verified_at'] = Carbon::now();
            $item->update($vars);
        }

        if ($item->isRoleEditable() && method_exists($item, 'roles')) {
            if (!count(array_intersect($request->input('role_ids'), $item->roles()->pluck('id')->toArray()))) {
                activity()
                    ->causedBy($request->user())
                    ->performedOn($item)
                    ->log("{$item->renderLogName()} roles has been updated.");
            }

            if ($request->filled('role_ids')) {
                $result = $item->syncRoles($request->input('role_ids'));
            }
        }

        return $item;
    }

    public function changePassword($current_password, $new_password, $causer = null) {
        $action = Hash::check($current_password, $this->password);

        if (!$action) {
            throw ValidationException::withMessages([
                'password' => 'The password you have entered does not match current password.',
            ]);
        }

        $isSameAsPrevious = Hash::check($new_password, $this->password);

        if ($isSameAsPrevious) {
            throw ValidationException::withMessages([
                'password' => 'Your new password cannot be the same as the old one.',
            ]);
        }

        if ($action && !$isSameAsPrevious) {
            $this->password = Hash::make($new_password);
            $this->save();


            if ($causer) {
                activity()
                    ->causedBy($causer)
                    ->performedOn($this)
                    ->log("{$this->renderLogName()} password has been changed.");
            }

        }

        return $action;
    }

    public function readAllNotifications() {
        $items = $this->unreadNotifications;

        if ($items->count()) {
            $items->markAsRead();
        } else {
            throw ValidationException::withMessages([
                'notification' => 'All notifications are already marked as read.',
            ]);
        }

        return true;
    }

    public function readNotification($id) {
        if ($notification = $this->unreadNotifications()->find($id)) {
            $notification->markAsRead();
        } else {
            throw ValidationException::withMessages([
                'notification' => 'Notification is already marked as read.',
            ]);
        }

        return true;
    }

    public function unreadNotification($id) {
        if ($notification = $this->readNotifications()->find($id)) {
            $notification->markAsUnread();
        } else {
            throw ValidationException::withMessages([
                'notification' => 'Notification is already marked as unread.',
            ]);
        }

        return true;
    }

    public function isRoleEditable() {
        return true;
    }

    public function broker() {
        return Password::broker();
    }

    public function archiveErrorMessage() {
        $result = $this->renderLogName();

        if ($this->isArchiveable()) {
            $result .= ' has already been archived.';
        } else {
            $result .= ' cannot be archived.';
        }

        return $result;
    }

    public function restoreErrorMessage() {
        $result = $this->renderLogName();

        if ($this->isRestorable()) {
            $result .= ' has already been restored.';
        } else {
            $result .= ' cannot be restored.';
        }

        return $result;
    }

    /**
     * @Render
     */

    public function renderName() {
        return ucwords($this->first_name . ' ' . $this->last_name);
    }

    public function renderImagePath($column = 'image_path') {
        $path = '/images/avatar.png';

        if ($this[$column]) {
            $path = Storage::url($this[$column]);
        }

        return $path;
    }
    
    public function renderFilePath($column = 'file_path') {
        $path = '/images/avatar.png';

        if ($this[$column]) {
            $path = Storage::url($this[$column]);
        }

        return $path;
    }
}