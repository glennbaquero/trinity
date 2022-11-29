<?php

namespace App\Http\Controllers\Admin\Invoices;


use Illuminate\Http\Request;
use App\Http\Controllers\FetchController;

use App\Models\Invoices\Invoice;
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
        $this->class = new Invoice;
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
        
        if ($this->request->filled('status')) {
            $status = $this->request->input('status') === 2 ? null : $this->request->input('status');
            $query = $query->where('status_id', $status);
        }

        if($this->request->filled('secretary')) {
            $secretary = User::find($this->request->id);
            if(!$this->request->filled('doctor')) {
                $ids = $secretary->getPatients();
            } else {
                $doctor = $secretary->doctors->where('id', $this->request->doctor)->first();
                $ids = $doctor->patients()->pluck('id')->toArray();
            }
            $query = $query->whereIn('user_id', $ids);                

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
                'status_id' => $item->status_id,
                'user' => $item->user ? $item->user->renderFullName() : null,
                'status' => $item->renderOrderStatus(),
                'completed' => $item->completed,
                'payment_method' => $item->renderPaymentType('label'),
                'payment_method_class' => $item->renderPaymentType('class'), 
                'deposit_slip' => $item->deposit_slip_path, 
                'renderDepositSlipPath' => $item->renderImagePath('deposit_slip_path'), 
                'invoice_number' => $item->invoice_number,
                'code' => $item->code,
                'grand_total' => $item->renderPrice('grand_total', 'Php'),
                'created_at' => $item->renderCreatedAt(),
                'updated_at' => $item->renderDate('updated_at'),
                'deleted_at' => $item->deleted_at
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

        if ($id) {
            $item = Invoice::withTrashed()->find($id);
            $item->archiveUrl = $item->renderArchiveUrl();
            $item->restoreUrl = $item->renderRestoreUrl();
        }

        $specializations = Specialization::get();

        return response()->json([
            'item' => $item,
        ]);

    }

}
