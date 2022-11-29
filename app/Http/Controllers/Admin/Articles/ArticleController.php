<?php

namespace App\Http\Controllers\Admin\Articles;

use App\Http\Requests\Admin\Articles\ArticleStoreRequest;
use App\Http\Controllers\Controller;

use App\Models\Articles\Article;
use App\Models\Users\Doctor;
use App\Models\Users\User;
use App\Services\PushService;
use App\Services\PushServiceDoctor;

use DB;

class ArticleController extends Controller
{
    
    public function __construct() {
        $this->middleware('App\Http\Middleware\Admin\Articles\ArticleMiddleware', 
            ['only' => ['index', 'create', 'update', 'archive', 'restore']]
        );
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.articles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleStoreRequest $request)
    {
        DB::beginTransaction();
        $item = Article::store($request);
        
        if($request->hasFile('downloadable_path')) {
            $path = $request->file('downloadable_path')->store('articles-docs', 'public');
            $item->files()->create([
                'path' => $path
            ]);
        }
       
        if($request->related_article_ids) {
            $item->relatedArticles()->attach($request->related_article_ids);
        }

        DB::commit();
        $message = "You have successfully created {$item->id}";

        if($request->for_doctor == 2) {
            $doctors = Doctor::all();
            $pushDoctor = new PushServiceDoctor('New article ', $item->title.' has been released check them out now!');
            $pushDoctor->pushToMany($doctors);
        } else {
            $users = User::whereHas('user_setting', function($q) { 
                        $q->where('uploaded_article', true); 
                    })->get();
            $push = new PushService('New article', $item->title.' has been released check them out now!');
            $push->pushToMany($users);
        }
        
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Article::withTrashed()->findOrFail($id);
       
        return view('admin.articles.show', [
            'item' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleStoreRequest $request, $id)
    {
        $item = Article::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->title}";

        DB::beginTransaction();
            $item = Article::store($request, $item);

            if($request->related_article_ids) {
                $item->relatedArticles()->sync($request->related_article_ids);
            }
            
            if($request->hasFile('downloadable_path')) {
                $item->files()->delete();
                $path = $request->file('downloadable_path')->store('articles-docs', 'public');
                $item->files()->create([
                    'path' => $path
                ]);
            }
        DB::commit();

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $articles
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = Article::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->title}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Article  $car
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = Article::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->name}",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Show comments on a specific article
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showComments($id)
    {
        $cmmts = Article::findOrFail($id)->comments()->withTrashed()->get();

        $comments = [];

        foreach($cmmts as $cmmt) {
            array_push($comments, [
                'comment' => $cmmt,
                'user' => $cmmt->commentable,
                'deleted_at' => $cmmt->deleted_at,
                'replies' => $cmmt->formatReplies(),
                'approvedURL' => $cmmt->approveComment(),
                'restoreURL' => $cmmt->restoreURL(),
                'archiveURL' => $cmmt->archiveURL(),
            ]);
        }

        return response()->json(compact('comments'));
    }

    /**
     * Archive a comment
     *
     * @param  int  $id
     * @param  int  $commentId
     * @return \Illuminate\Http\Response
     */
    public function archiveComment($id, $commentId)
    {
        $comments = Article::findOrFail($id)->comments();

        $selectedComment = $comments->withTrashed()->findOrFail($commentId);
        $selectedComment->archive();

        return response()->json([
            'id' => $selectedComment->id,
            'message' => 'Comment successfully archived'
        ]);
    }
}
