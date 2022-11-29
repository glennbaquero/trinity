<?php

namespace App\Models\Articles;

use App\Models\Users\User;
use App\Models\Users\Doctor;
use App\Models\Articles\Article;
use App\Models\Articles\Reply;

use App\Extendables\BaseModel as Model;

use App\Traits\HelperTrait;
use Carbon\Carbon;

class Comment extends Model
{

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	public function user()
	{
		return $this->belongsTo(User::class,'commentable_id');
	}

	public function doctor()
	{
		return $this->belongsTo(Doctor::class,'commentable_id');
	}
    
	public function commentable() 
	{
		return $this->morphTo();
	}

	public function replies()
	{
		return $this->hasMany(Reply::class);
	}

	public static function store($request, $item = null, $columns = ['approval_date'])
    {

        $vars['approval_date'] = Carbon::now();
        $item->update($vars);
        
        return $item;
    }

	/*
	|--------------------------------------------------------------------------
	| Methods
	|--------------------------------------------------------------------------
	*/
	public function format()
	{
		$comment = [];
		array_push($comment, [
			'approvedURL' => $this->approveComment(),
		]);

		return $comment;
	}

	public function approveComment() 
	{
		return route('admin.comments.update', $this->id);
	}

	public function archiveURL()
	{
		return route('admin.comments.archive', $this->id);
	}

	public function restoreURL()
	{
		return route('admin.comments.restore', $this->id);
	}

	public function formatReplies()
	{
		$rplies = $this->replies()->withTrashed()->get();
		$replies = [];

		foreach ($rplies as $reply) {
			array_push($replies, [
				'user' => $reply->replyable,
				'content' => $reply->reply,
				'created_at' => $reply->created_at,
				'deleted_at' => $reply->deleted_at,
				'approval_date' => $reply->approval_date,
				'approvalURL' => $reply->approvedURL(),
				'restoreURL' => $reply->restoreURL(),
				'archiveURL' => $reply->archiveURL(),
			]);
		}
		return $replies;
	}

}
