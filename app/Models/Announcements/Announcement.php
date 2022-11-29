<?php

namespace App\Models\Announcements;

use App\Extendables\BaseModel as Model;

use App\Traits\FileTrait;

use App\Models\Users\Doctor;
use App\Models\Users\User;
use App\Models\Users\MedicalRepresentative;

use App\Services\PushService;
use App\Services\PushServiceDoctor;
// use App\Services\PushServiceTeam;

use App\Notifications\Admin\Announcements\AnnouncementNotification;
use Notification;

use Carbon\Carbon;

class Announcement extends Model
{


    use FileTrait;

	const APP_ALL = 0;
	const APP_CARE = 1;
	const APP_DOC = 2;
	const APP_TEAM = 3;

    /**
     * Attributes
     */

    protected $appends = ['image'];

    /**
     * Get attribute image path
     * 
     *
     */
    public function getImageAttribute()
    {
        if($this->image_path) {
            return $this->renderImagePath('image_path');
        }
    }



	/**
	 * Relationship
	 */

	public function announcementType() {
		return $this->belongsTo(AnnouncementType::class)->withTrashed();
	}

    /**
	 * @Getters
	 */

	public static function store($request, $item = null, $columns = ['announcement_type_id', 'announce_to', 'title', 'description', 'event_date'])
    {

        $vars = $request->only($columns);
        $vars['image_path'] = static::storeImage($request, $item, 'image_path', 'announcement-banners');

        if (!$item) {
            $item = static::create($vars);
            $item->notifyUsers();
        } else {
            $item->update($vars);
        }


        return $item;
    }

    public static function getAppCompatible() {
        return [
            ['value' => static::APP_ALL, 'label' => 'All Apps', 'class' => 'bg-primary'],
            ['value' => static::APP_CARE, 'label' => 'Care App', 'class' => 'bg-warning'],
            ['value' => static::APP_DOC, 'label' => 'Doc App', 'class' => 'bg-green'],
            ['value' => static::APP_TEAM, 'label' => 'Team App', 'class' => 'bg-danger'],
        ];
    }

    public function notifyUsers() {

        $users = User::all();

		$doctors = Doctor::all();
		$teams = MedicalRepresentative::all();

    	switch ($this->announce_to) {
    		case 1:
                $push = new PushService($this->title, $this->description, $this->image_path);

                if($this->announcementType->name === 'Promo') {
                    $users = User::whereHas('user_setting', function($q) {
                        $q->where('promo', true);
                    })->get();

                    $this->sendNotification($users);
                    $push->pushToMany($users);
                } else {
                    $this->sendNotification($users);
                    $push->pushToMany($users);
                }
    			break;
    		case 2:
                $push = new PushServiceDoctor($this->title, $this->description, $this->image_path);
                $this->sendNotification($doctors);
    			$push->pushToMany($doctors);
    			break;
    		case 3:
	            // $team = new PushServiceTeam($this->title, $this->description);
    			// $team->pushToMany($users);
    			break;

    		default:
	            $care = new PushService($this->title, $this->description, $this->image_path);
	            $doc = new PushServiceDoctor($this->title, $this->description, $this->image_path);
	            // $team = new PushServiceTeam($this->title, $this->description);
                if($this->announcementType->name === 'Promo') {
                    $users = User::whereHas('user_setting', function($q) {
                        $q->where('promo', true);
                    })->get();

                    $this->sendNotification($users);
                    $care->pushToMany($users);
                } else {
                    $this->sendNotification($users);
                    $care->pushToMany($users);
                }
                
                $doc->pushToMany($doctors);
                // $this->sendNotification($users);
                $this->sendNotification($doctors);
    			// $team->pushToMany($teams);
    			break;
    	}
    }

    public function sendNotification($users)
    {
        foreach($users as $user) {
            $title = $this->announcementType->name;

            $event_date = $this->event_date ?  Carbon::create($this->event_date)->toDayDateTimeString(): '';

            $message = '<b>Announcement:</b> <br>'.
                        '<br>'.$this->announcementType->name . ' - ' .  $this->title . '<br>'.
                        '<br>'.$this->description.'<br>'.
                        $event_date;

            Notification::send($user, new AnnouncementNotification($title, $message));
        }

    }

    /**
     * Fetch announcements
     *
     * @param  int $type
     */
    public static function fetch($type)
    {
        $announcements = static::where(['announce_to' => $type])->orWhere(['announce_to' => static::APP_ALL])->latest()->get();
        return $announcements;
    }

	public function renderArchiveUrl() {
	    return route('admin.announcements.archive', $this->id);
	}

	public function renderRestoreUrl() {
	    return route('admin.announcements.restore', $this->id);
	}

	public function renderShowUrl() {
	    return route('admin.announcements.show', $this->id);
	}

	public function renderAppCompatibilityLabel() {
        return $this->renderConstants(static::getAppCompatible(), $this->announce_to, 'label', 'value');
    }

    public function renderAppCompatibilityClass() {
        return $this->renderConstants(static::getAppCompatible(), $this->announce_to, 'class', 'value');
    }
}
