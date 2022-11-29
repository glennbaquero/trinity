<?php

namespace App\Http\Controllers\Admin\Articles;

use App\Http\Controllers\FetchController;

use App\Models\Articles\Article;
use App\Models\Articles\ArticleCategory;

class ArticleFetchController extends FetchController
{
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
        $result = [];

        foreach($items as $item) {
            $data = $this->formatItem($item);
            array_push($result, $data);
        }

        return $result;
    }

    /**
     * Build array data
     * 
     * @param  App\Contracts\AvailablePosition
     * @return array
     */
    protected function formatItem($item)
    {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'category' => $item->articleCategory,
            'app' => $item->renderAppAvailability(),
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
            'deleted_at' => $item->deleted_at,
            'created_at' => $item->renderCreatedAt(),
        ];
    }

    protected function sortQuery($query) {

        switch ($this->sort) {
            default:
                    $query = $query->orderBy($this->sort, $this->order);
                break;
        }

        return $query;
    }

    public function fetchView($id = null) {
        $item = null;
        $articles = Article::all();
        $article_categories = ArticleCategory::all();

        if ($id) {
            $articles = Article::where('id','<>',$id)->get();
        	$item = Article::withTrashed()->findOrFail($id);
        	$item->brand = $item->brand;
        	$item->type = $item->type;
            $item->name = $item->name;
        	$item->for_doctor = $item->for_doctor;
            $item->related_article_ids = $item->relatedArticles->pluck('id');
            $item->archiveUrl = $item->renderArchiveUrl();
            $item->restoreUrl = $item->renderRestoreUrl();
            $item->renderImage = $item->renderImagePath('image_path');
            $item->downloadableUrl = count($item->files) ? 'storage/'.$item->files()->first()->path : null;
        }

    	return response()->json([
    		'item' => $item,
            'articles' => $articles,
            'article_categories' => $article_categories,
    	]);
    }

    public function getVariants($items) {
        $result = [];

        foreach($items as $item) {
            array_push($result, [
                'name' => $item->name,
                'price' => $item->price,
                'description' => $item->description,
                'renderImage' => $item->renderImagePath('image')
            ]);
        }

        return $result;

    }
}
