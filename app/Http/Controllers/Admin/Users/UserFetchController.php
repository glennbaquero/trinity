<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\FetchController;
use App\Models\Users\User;
use App\Models\Users\Doctor;

class UserFetchController extends FetchController
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
        if ($this->request->filled('status')) {

            if($this->request->status == 1){
                $query = $query->where('email_verified_at','<>', null);
            }
            if($this->request->status == 0){
                $query = $query->where('email_verified_at', null);
            }

        }

        if ($this->request->filled('type')) {
            $query = $query->where('type', $this->request->type);
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
            'name' => "{$item->first_name} {$item->last_name}",
            'email' => $item->email,
            'email_verified_at' => $item->email_verified_at,
            'status' => $item->renderStatus(),
            'user_type' => $item->renderUserType(),
            'points' => $item->points->sum('points'),
            'doctors' => $item->doctors,
            'wallets' => $item->countCredits(true),
            'created_at' => $item->renderCreatedAt(),
            'showUrl' => $item->renderShowUrl(),
            'deleted_at' => $item->deleted_at,
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
            'manageCreditsUrl' => $item->renderManageCreditsUrl()
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
        $item = [];

        if ($id) {
            $item = User::withTrashed()->findOrFail($id);
            $item->status = $item->approved;
            $item->doctor_ids = $item->doctors->pluck('id');
            $item->archiveUrl = $item->renderArchiveUrl();
            $item->restoreUrl = $item->renderRestoreUrl();
            $item->renderImage = $item->renderImagePath();
            $item->email_verified_at = $item->email_verified_at;
            $item->verificationImage = $item->renderImagePath('verification_image_path');
        }

        $doctors = Doctor::get();
        $types = User::renderUserTypes();

        return response()->json([
            'item' => $item,
            'doctors' => $doctors,
            'types' => $types
        ]);
    }
}
