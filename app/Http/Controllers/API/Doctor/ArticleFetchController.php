<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\FetchController;
use Illuminate\Http\Request;

use App\Models\Articles\Article;
use Carbon\Carbon;

class ArticleFetchController extends FetchController
{

    protected $request;

    public function __construct(Request $request)
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
        $this->total = 100;
    }

    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */

    public function filterQuery($query)
    {
        $now = Carbon::now();

        if($this->request->filled('id') ){
            $category_id = Article::find($this->request->id)->category_id;
            $query = $query->where('category_id', $category_id)->where('id','<>',$this->request->id)->where('for_doctor',2);
        }

        if(!empty($this->request->filter) && $this->request->filled('filter')) {
            if(count($this->request->filter) >= 2 && $this->request->filter[1] == 1){
                $query = $query->whereMonth('date', $now->month);
            } else if(count($this->request->filter) < 2 && $this->request->filter[0] == 0){
                $query = $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            }
        } else if($this->request->filled('title')) {
            $query = $query->where('title', 'like', '%'.$this->request->title.'%');
        }

        return $query->where('for_doctor', 2)->orderBy('id', 'desc');
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
            'overview' => $item->overview,
            'short_overview' => str_limit($item->overview, 15),
            'image_path' => url($item->renderImagePath('image_path')),
            'full_image' => url($item->renderImagePath('image_path')),
            'date' => $item->renderShortDate()
        ];
    }
}
