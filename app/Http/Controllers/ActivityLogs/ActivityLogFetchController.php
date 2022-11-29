<?php

namespace App\Http\Controllers\ActivityLogs;

use Illuminate\Http\Request;
use App\Http\Controllers\FetchController;

use App\Models\ActivityLogs\ActivityLog;

class ActivityLogFetchController extends FetchController
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new ActivityLog;
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
        if ($this->request->filled('sample') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Samples\SampleItem',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('articles') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Articles\Article',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('shipping-standards') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\ShippingMethod\Standard',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('shipping-expresses') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\ShippingMethod\Express',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('bank-details') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\BankDetails\BankDetail',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('admin') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Users\Admin',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('profile')) {
            $filters = [
                'subject_type' => 'App\Models\Users\Admin',
                'subject_id' => $this->request->user()->id,
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('roles') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Roles\Role',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('pagecontents') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Pages\Page',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('pageitems') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Pages\PageItem',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('subject_type')) {
            $query = $query->where('subject_type', $this->request->input('subject_type'));
        }

        /** Invoices Logs */
        if ($this->request->filled('invoices') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Invoices\Invoice',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        } 

        /** Call Logs */
        if ($this->request->filled('calls') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Calls\Call',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }


        /** Target Call Logs */
        if ($this->request->filled('target-calls') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Calls\TargetCall',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        /** Products Logs */
        if ($this->request->filled('products') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Products\Product',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        } 

        /** Inventories Logs */
        if ($this->request->filled('inventories') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Inventories\Inventory',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        } 

        /** Specializations Logs */
        if ($this->request->filled('specializations') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Specializations\Specialization',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        /** StatusTypes Logs */
        if ($this->request->filled('status-types') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\StatusTypes\StatusType',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('medreps') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Users\MedicalRepresentative',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('rewards') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Rewards\Reward',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('provinces') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Provinces\Province',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('cities') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Cities\City',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('users') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Users\User',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }


        if ($this->request->filled('doctors') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Users\Doctor',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }


        if ($this->request->filled('sponsorships') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Rewards\Sponsorship',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('prescriptions') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Prescriptions\Prescription',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('faqs') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Faqs\Faq',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('faq-categories') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Faqs\FaqCategory',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('announcement-types') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Announcements\AnnouncementType',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('announcements') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Announcements\Announcement',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('areas') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Areas\Area',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('shipping-matrixes') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\ShippingMatrixes\ShippingMatrix',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }
        if ($this->request->filled('product-parents') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Products\Product',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('credit-packages') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\CreditPackages\CreditPackage',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }        

        if ($this->request->filled('payouts') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Payouts\Payout',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }         
        
        if ($this->request->filled('pharmacies') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Pharmacies\Pharmacy',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }

        if ($this->request->filled('vouchers') && $this->request->filled('id')) {
            $filters = [
                'subject_type' => 'App\Models\Vouchers\Voucher',
                'subject_id' => $this->request->input('id'),
            ];

            $query = $query->where($filters);
        }


        return $query;

    }

    public function additionalQuery($query)
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

            array_push($result, array_merge($data, [
                'id' => $item->id,
                'name' => $item->renderName(),
                'caused_by' => $item->renderCauserName(),
                'show_causer' => $item->renderCauserShowUrl(),
                'subject_type' => $item->renderSubjectType(),
                'subject_name' => $item->renderSubjectName(),
                'created_at' => $item->renderDate(),
            ]));
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
}
