<?php

namespace App\Models\Articles;

use App\Models\Articles\Article;

use App\Extendables\BaseModel as Model;

use App\Traits\HelperTrait;

use Carbon\Carbon;

class ArticleCategory extends Model
{

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/

	public function articles()
	{
		return $this->hasMany(Article::class);
	}

	/*
	|--------------------------------------------------------------------------
	| Methods
	|--------------------------------------------------------------------------
	*/

	public static function store($request, $item = null, $columns = ['name'])
    {

        $vars = $request->only($columns);

        if (!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }
        
        return $item;
	}
	
	
	public function renderArchiveUrl()
	{
		return route('admin.article-categories.archive', $this->id);
	}

	public function renderRestoreUrl()
	{
		return route('admin.article-categories.restore', $this->id);
	}

	public function renderShowUrl() {
	    return route('admin.article-categories.show', $this->id);
	}

}