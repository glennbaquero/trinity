<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\FetchController;

use App\Models\Products\Product;
use App\Models\Products\ProductParent;
use App\Models\Specializations\Specialization;

class ProductFetchController extends FetchController
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Product;
    }

    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {
        if ($this->request->filled('specialization')) {
            $specialization = $this->request->specialization;
            $specializationProductIds = Specialization::find($specialization)->products()->pluck('id')->toArray();
            $query = $query->whereIn('id', $specializationProductIds);
        }

        if($this->request->filled('parent_id')) {
            $query = $query->where('parent_id', $this->request->input('parent_id'));
        }

        if($this->request->filled('type')) {
            $query = $query->where('type', $this->request->input('type'));
        }

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
            $data = array_merge($data, [
                'id' => $item->id,
                'type' => $item->renderType(),
                'image_path' => $item->renderImagePath(),
                'name' => $item->name,
                'brand_name' => $item->brand_name,
                'generic_name' => $item->generic_name,
                'sku' => $item->sku,
                'product_size' => $item->product_size,
                'prescriptionable' => $item->prescriptionable,
                'prescriptionable_text' => $item->renderPrescriptionable(),
                'price' => $item->renderPrice('price', 'Php'),
                'client_points' => $item->client_points,
                'doctor_points' => $item->doctor_points,
                'created_at' => $item->renderCreatedAt(),
                'deleted_at' => $item->deleted_at,
            ]);

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
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {

        $item = null;
        $images = [];
        $parents = ProductParent::get();

        if ($id) {
            $item = Product::withTrashed()->find($id);
            $item->specializations = $item->specializations()->pluck('id');
            $item->image_path = $item->renderImagePath();
            $item->archiveUrl = $item->renderArchiveUrl();
            $item->restoreUrl = $item->renderRestoreUrl();
        }

        $specializations = Specialization::get();

        return response()->json([
            'item' => $item,
            'specializations' => $specializations,
            'parents' => $parents
        ]);

    }
}
