<?php

namespace App\Models\Articles;

use App\Models\Files\File;

use App\Extendables\BaseModel as Model;
use App\Helpers\StringHelpers;
use App\Traits\FileTrait;
use App\Helpers\FileHelpers;
use Laravel\Scout\Searchable;

use App\Models\Users\Doctor;
use App\Models\Users\User;

use App\Notifications\Admin\Articles\ArticleNotification;
use Notification;

use Carbon\Carbon;

class Article extends Model
{
    use FileTrait;
    use Searchable;
    
    protected static $logAttributes = ['title', 'overview', 'date', 'image_path'];
	protected $dates = ['date'];
	
	protected $appends = ['full_image'];

    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	public function files()
	{
		return $this->morphMany(File::class, 'fileable');
	}

	public function comments()
	{
	    return $this->hasMany(Comment::class);
	}

	public function relatedArticles() 
	{
		return $this->hasMany(Article::class, 'category_id','category_id');
	}

	public function articleCategory() 
	{
		return $this->belongsTo(ArticleCategory::class, 'category_id');
	}

	public function toSearchableArray()
	{
		return [
			'id' => $this->id,
			'title' => $this->title
		];
	}
	
	/**
	 * @Getters
	 */
	public static function store($request, $item = null, $columns = ['title', 'overview', 'date', 'image_path', 'for_doctor', 'category_id'])
    {

        $vars = $request->only($columns);
        $vars['image_path'] = static::storeImage($request, $item, 'image_path', 'articles');

        if (!$item) {
			$item = static::create($vars);
			$item->notifyUsers();
        } else {
            $item->update($vars);
        }
        
        return $item;
	}
	
	public function notifyUsers() {

		$users = User::whereHas('user_setting', function($q) { 
			$q->where('uploaded_article', true); 
		})->get();

		$doctors = Doctor::all();


    	switch ($this->for_doctor) {
    		case 1:
                $this->sendNotification($users);
    			break;
    		case 2:
                $this->sendNotification($doctors);
    			break;
    	}
	}
	
	public function sendNotification($users)
    {
        foreach($users as $user) {
            $title = $this->title;
            
            $event_date = Carbon::create($this->create_at)->toDayDateTimeString();

            $message = '<b>New Artircle: </b>'.$this->title.'<br>'.$event_date;
                        
            Notification::send($user, new ArticleNotification($title, $message));
        }

    }

	public function renderArchiveUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'articles.archive', $this->id);
	}

	public function renderRestoreUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'articles.restore', $this->id);
	}

	public function renderShowUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'articles.show', $this->id);
	}

	public function renderImage($column = 'image_path') {
	    return 'storage/'. $this->column;
	}

	public function renderShortTitle() {
		return substr($this->title, 0, 7) . '...';
	}

	public function renderShortDate() {
		return $this->date->format('m/d');
	}

	public function renderAppAvailability() {
		return $this->for_doctor == 1 ? 'Care' : 'Doc';
	}

	/**
	 * Appends full image attribute
	 * 
	 * @return string
	 */
	public function getFullImageAttribute()
	{
		return url($this->renderImagePath());
	}

	public function renderShortString() 
	{
		return str_limit(strip_tags($this->overview), 45); 
	}

	/**
	 * Return related articles
	 * 
	 * @return object
	 */

}
