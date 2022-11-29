<?php

namespace App\Models\Articles;

use App\Extendables\BaseModel as Model;
use App\Models\Users\User;
use App\Models\Users\Doctor;
use App\Models\Articles\Comment;
use App\Traits\HelperTrait;

use Carbon\Carbon;

class Reply extends Model
{
	protected $dates = ['created_at'];


    public function user()
    {
        return $this->belongsTo(User::class,'replyable_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class,'replyable_id');
    }

    public function replyable()
    {
    	return $this->morphTo();
    }

    public function comment()
    {
    	return $this->belongsTo(Comment::class);
    }

    public static function store($request, $item = null, $columns = ['approval_date'])
    {

        $vars['approval_date'] = Carbon::now();

        $item->update($vars);

        return $item;
    }

    public function approvedURL()
    {
    	return route('admin.replies.update', $this->id);
    }

    public function archiveURL()
    {
    	return route('admin.replies.archive', $this->id);
    }

    public function restoreURL()
    {
    	return route('admin.replies.restore', $this->id);
    }
}
