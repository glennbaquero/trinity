<?php

namespace App\Http\Controllers\Admin\Invoices;

use App\Http\Controllers\FetchController;

use App\Models\Invoices\Invoice;
use App\Models\Invoices\InvoiceItem;

class InvoiceItemFetchController extends FetchController
{

    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new InvoiceItem;
    }

    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {

        /**
         * Queries
         * 
         */

        if($this->request->filled('invoice')) {
            $query = $query->where('invoice_id', $this->request->input('invoice'));
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

        foreach ($items as $item) {
            $data = [
                'id' => $item->id,
                'product_image'=> $item->product->renderImagePath(),
                'doctor_id' => $item->doctor_id,
                'doctor' => $item->renderDoctor(),
                'prescription_path' => $item->renderPrescriptionPath(),
                'consultation_id' => $item->consultation_id,
                'consultation' => $item->renderConsultation(),
                'consultation_show_url' => $item->consultation ? $item->consultation->renderShowUrl(): '',                
                'name' => $item->product->name,
                'size' => $item->product->product_size,
                'quantity' => $item->quantity,
                'price' => $item->renderPrice('price', 'Php'),
            ];

            array_push($result, $data);
        }
        return $result;
    }

}
