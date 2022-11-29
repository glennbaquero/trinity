<?php

namespace App\Http\Controllers\API\Care\Invoices;

use App\Http\Controllers\FetchController;

use App\Models\Users\User;

class InvoiceFetchController extends FetchController
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new User;
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
        $query = $query->where(['user_id' => $this->request->user()->id, 'status' => 3]);
        return $this->request->user();
    }

    /**
     * Custom formatting of data
     * 
     * @param Illuminate\Support\Collection $items
     * @return array $result
     */
    public function formatData($items)
    {
        $userInvoices = $this->request->user()->invoices()->orderBy('created_at', 'desc')->get();
        $invoices = $userInvoices->map(function($invoice) {
            return $invoice->getUserInvoice();
        });

        return $invoices->all();
    }
}
