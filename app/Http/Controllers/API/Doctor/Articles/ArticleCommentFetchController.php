<?php

namespace App\Http\Controllers\API\Doctor\Articles;

use App\Http\Controllers\FetchController;
use Illuminate\Http\Request;

use App\Models\Articles\Comment;
use App\Models\Articles\Article;
use Carbon\Carbon;

class ArticleCommentFetchController extends FetchController
{
	protected $request;

	public function __construct($request) 
	{
		$this->request = $request;
	}

    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Article;
    }

    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {
        return $query;
    }

    /**
     * Custom formatting of data
     * 
     * @param Illuminate\Support\Collection $items
     * @return array $result
     */
    public function formatData($items)
    {
        $comments = [];

        if($this->request->filled('id')) {
            $article = Article::where('id', $this->request->id)->first();
            
            $columns = ['id','comment','commentable_id','created_at'];
            $comments = $article->comments()->with('replies')->get($columns);

            $comments = collect($comments)->map(function ($comment) {
                $comment->full_name = $comment->doctor->renderFullName();
                $comment->image_path = $comment->doctor->full_image;
                $comment->date = $comment->renderCreatedAt();

                if($comment->replies) {
                    $comment->replies = collect($comment->replies)->map(function ($reply) {
                        $reply->full_name = $reply->doctor->renderFullName();
                        $reply->image_path = $reply->doctor->full_image;
                        $reply->date = $reply->renderCreatedAt();
                        $hiddenColumn = [
                            'doctor','updated_at',
                            'approval_date','comment_id',
                            'comment_id','deleted_at',
                            'replyable_type','replyable_id','created_at'];
                        return  $reply->makeHidden($hiddenColumn)->toArray();
                    });
                }
                return $comment->makeHidden('doctor')->toArray();
            });
        }

        return $comments;
    }

    public function formatReplies($comment)
    {
        $replies = [];

        foreach ($comment->replies as $reply) {
            array_push($replies, [
                'user' => $reply->replyable,
                'content' => $reply->reply,
                'created_at' => $reply->renderDate(),
            ]);
        }
        return $replies;
    }


}
