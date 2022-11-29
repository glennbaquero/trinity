<?php

namespace App\Http\Controllers\API\Care\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\FetchController;

use App\Models\Users\User;
use App\Models\Specializations\Specialization;
use App\Models\Products\ProductParent;

class ProductParentFetchController extends FetchController
{
    public $request;

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
        $this->class = new ProductParent;
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
        $items = [];
        $parents = $this->class;

        if($this->request->filled('query') && $this->request->input('query')) {
            $parents = $parents::search($this->request->input('query'));
        }

        $parents = $parents->get();

        foreach ($parents as $parent) {
            array_push($items,[
               'id' => $parent->id,
               'specialization' => $parent->renderSpecialization(),
               'name' => $parent->name,
               'description' => $parent->description,
               'image' => url($parent->renderImagePath()),
            ]);
        }

        return $items;
    }

}
