<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\Controller;
use App\Notifications\Doctor\ArticleFullVersion;
use Illuminate\Http\Request;

use App\Http\Controllers\API\Doctor\Articles\ArticleCommentFetchController;
use App\Http\Controllers\API\Doctor\ArticleFetchController;

use App\Models\Articles\Article;
use App\Models\Articles\Comment;
use App\Models\Articles\Reply;

use Carbon\Carbon;
use DB;

class ArticleController extends Controller
{
    /**
     * Fetch all articles
     * 
     * @return Illuminate\Http\Response
     */
    public function index(Article $article)
    {
        $article = $article->newQuery();

        
        if(request()->search) {
            $article->where('title', 'like', '%' . request()->search . '%');
        }

        $article->where('for_doctor', 2)->orderby('id', 'desc');
        
        return response()->json([

            'data' => $article->latest()->paginate(10),
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

    /**
     * Fetching of comments
     * 
     * @param Illuminate\Http\Request\
     */
    public function show(Request $request) 
    {
        $fetch_comments = new ArticleCommentFetchController($request);
        $comments = $fetch_comments->fetch($request);

        $related_articles = new ArticleFetchController($request);
        $related = $related_articles->fetch($request);
  
        return response()->json([
            'comments' => $comments->original['items'],
            'related_articles' => $related->original['items'],
        ]);
    }

    /**
     * Reply to comment
     * 
     * @param Illuminate\Http\Request\
     */
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

    /**
     * Request full version
     * 
     * @param Illuminate\Http\Request\
     */
    public function download(Request $request)
    {
        $article = Article::find($request->article);
        
        $request->user()->notify(new ArticleFullVersion($article));

        return response()->json();
    }

   

}
