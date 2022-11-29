<?php

namespace App\Http\Controllers\Admin\RequestClaimReferrals;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\RequestClaimReferrals\RequestClaimReferralRequest;

use App\Models\Referrals\RequestClaimReferral;

use DB;

class RequestClaimReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.request-claim-referrals.index');
    }

    /**
     * Display form for updating specified request
     * 
     * @param  int $id
     * @param  string $action
     * @return \Illuminate\Http\Response
     */
    public function displayForm($id, $action)
    {   
        $item = RequestClaimReferral::find($id);
        $submitUrl = $item->renderSubmitUrl($id, $action);

        return view('admin.request-claim-referrals.update-status', [
            'item' => $item,
            'submitUrl' => $submitUrl,
            'action' => $action
        ]);
    }

    /**
     * Update status of specified request
     *  
     * @param  Request $request
     * @param  int  $id
     * @param  string  $action
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(RequestClaimReferralRequest $request, $id, $action)
    {
        $requestReferral = RequestClaimReferral::find($id);
        $requestReferral->updateStatus($action, $request);

        return response()->json([
            'message' => 'Request has been successfully updated',
            'redirect' => route('admin.request-claim-referrals.index')
        ]);
    }

    /**
     * Remove the specified request from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = RequestClaimReferral::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->renderName()}",
        ]);
    }

    /**
     * Restore the specified request from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = RequestClaimReferral::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restore {$item->renderName()}",
        ]);
    }
}
