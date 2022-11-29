<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;

abstract class FetchController extends Controller
{
    protected $user = null;

    protected $request = null;
    protected $class = '';

    protected $sort = 'id';
    protected $order = 'asc';

    protected $total = 3;
    protected $limits = [10, 15, 20];

    protected $has_search = true;


    /**
     * Set the object to be used by the controller
     *
     * @var $class Class name of the object
     */
    abstract protected function setObjectClass();

    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    abstract protected function filterQuery($query);  

    /**
     * Build an array w/ all the needed fields
     *
     * @return array
     */
    abstract protected function formatData($items);  


    /**
     * Set all needed variables
     */
    protected function init($request)
    {
        /* Get default variable */
        $this->user = $request->user();
        $this->request = $request;


        /* Set object class */
        $this->setObjectClass();   
    }

    /**
     * Fetch the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        /* Initialize needed vars */
        $this->init($request);


        /* Set default parameters */
        $this->setParameters($request);


        /* Set storage vars */
        $items = [];
        $pagination = [];

        /* Perform needed queries */
        $collections = $this->fetchQuery($request);

        /* Check if pagination is disabled  */
        if($request->has('nopagination') || $request->has('forselect') || $request->has('nojson')) {

            $items = $this->formatData($collections);
            return $items;

        } else {
            $items = $this->formatData($collections->items());
            $pagination = $this->getPagination(json_decode($collections->toJson()));
        }

        return response()->json([
            'items' => $items,
            'pagination' => $pagination
        ]);
    }

    /**
     * Create query
     * 
     * @return Illuminate\Pagination\Paginator
     */
    protected function fetchQuery()
    {   
        $query = $this->class;
        /* Fetch active or archived objects */
        if($this->request->filled('archived')) {
            $query = $query->onlyTrashed();
        }

        $query = $this->dateQuery($query);

        /* Run filters */
        $query = $this->filterQuery($query);

        /* Run search*/
        $query = $this->searchQuery($query);

        /* Run sorting */
        $query = $this->sortQuery($query);

        /* when table is not to be paginated */
        if($this->request->has('nopagination') || $this->request->has('forselect')) {
            return $query->get();
        }

        return $query->paginate($this->total);
    }

    protected function dateQuery($query) {
        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $startDate = Carbon::parse($this->request->input('start_date'))->format('Y-m-d') . " 00:00:00";
            $endDate = Carbon::parse($this->request->input('end_date'))->format('Y-m-d') . " 23:59:59";
            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query;
    }

    protected function searchQuery($query) {
        if($this->request->filled('search') && $this->has_search){
            if (config('web.tnt.refresh_on_query')) {
                $this->class::get()->searchable();
            }

            $query = $query->whereIn('id', $this->class::search($this->request->input('search'))->get()->pluck('id')->toArray());
        }

        return $query;
    }

    protected function sortQuery($query) {
        return $query->orderBy($this->sort, $this->order);
    }

    /**
     * Set general parameters
     */
    protected function setParameters()
    {
        /* Set column to sort  */
        if($this->request->has('sort')) {
            $this->sort = $this->request->sort;
        }

        /* Set column order  */
        if($this->request->has('order')) {
            $this->order = $this->request->order;        
        }

        /* Set total no. of item per page  */
        if($this->request->has('total')) {
            $this->total = $this->request->total;
        }
    }

    /**
     * Rename pagination keys
     * 
     * @param json
     * @return array
     */
    protected function getPagination($json)
    {
        return array(
            'prevpage' => $json->prev_page_url,
            'nextpage' => $json->next_page_url,
            'current' => $json->current_page,
            'last' => $json->last_page,
            'next' => $json->path,
            'total' => $json->total,
            'from' => $json->from,
            'to' => $json->to,
            'limits' => $this->limits,
        );
    }

    public function fetchPagePagination(Request $request, $id)
    {
        $this->init($request);

        $result = [
            'next_page' => null,
            'prev_page' => null,
        ];

        if (method_exists($this->class, 'generatePagePaginationUrls')) {
            $class = get_class($this->class);
            $result = $class::generatePagePaginationUrls($request, $id);
        }

        return response()->json($result);
    }
}