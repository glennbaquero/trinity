<?php

namespace App\Http\Controllers\API\Care\Articles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\Care\Articles\ArticleCommentFetchController;
use App\Http\Controllers\API\Care\Articles\ArticleFetchController;
use App\Models\Articles\Article;
use App\Models\Articles\Comment;
use App\Models\Articles\Reply;

use DB;
use Carbon\Carbon;

class ArticleController extends Controller
{

    /**
     * Fetching of related article
     * 
     * @param Illuminate\Http\Request\
     */
    public function show(Request $request) 
    {
        $article =  Article::find($request->id);

        if($article->category_id) {
            $related_articles = Article::where('category_id',$article->category_id)->where('id','<>',$request->id);
            $count = $related_articles->count();
            $related_articles = $related_articles->offset($request->item)->take(3)->get();

            $related_articles = collect($related_articles)->map(function ($article) { 
                $article->upload_at = $article->renderDateOnly();
                return $article;
            });
        }

        return response()->json([
            'related_articles' => $related_articles,
            'count' => $count,
        ]);
    }

    /**
     * Fetching of article comments
     * 
     * @param Illuminate\Http\Request\
     */
    public function comments(Request $request) 
    {
        $fetch_comments = new ArticleCommentFetchController($request);
        $comments = $fetch_comments->fetch($request);
  
        return response()->json([
            'comments' => $comments->original['items'],
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        DB::beginTransaction();

            $article = Article::find($request->id);
            $user->comments()->create([
                'approval_date' => Carbon::now(),
                'article_id' => $article->id,
                'comment' => $request->comment
            ]);

        DB::commit();

        return response()->json([
            'message' => 'successfully added a comment',
            'response' => 200
        ]);
    }

    public function viewAllArticles()
    {
        return response()->json([
            'data' => Article::paginate(5),
        ]);
    }

    public function addReplyToComment(Request $request)
    {
        $user = $request->user();

        DB::beginTransaction();
            $comment = Comment::find($request->id);
            $user->replies()->create([
                'approval_date' => Carbon::now(),
                'comment_id' => $comment->id,
                'reply' => $request->reply
            ]);
        DB::commit();

        return response()->json([
            'message' => 'success',
            'response' => 200
        ]);
    }
}
