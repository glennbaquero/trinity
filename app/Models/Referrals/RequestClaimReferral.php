<?php

namespace App\Models\Referrals;

use App\Extendables\BaseModel as Model;

use App\Notifications\Admin\RequestClaimReferrals\RequestClaimReferralNotification;

use App\Models\Users\Admin;
use App\Models\Users\User;
use App\Models\Vouchers\Voucher;
use App\Models\Referrals\SuccessReferral;

use Auth;
use Carbon\Carbon;

class RequestClaimReferral extends Model
{

    /*
    |--------------------------------------------------------------------------
    | @Const
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | @Relationships
    |--------------------------------------------------------------------------
    */

    public function requester()
    {
        return $this->belongsTo(User::class, 'request_by')->withTrashed();
    }
    
    public function successReferral()
    {
    	return $this->belongsTo(SuccessReferral::class);
    }

    public function distributor()
    {
    	return $this->belongsTo(Admin::class, 'distribute_by')->withTrashed();
    }

    public function disapprover()
    {
    	return $this->belongsTo(Admin::class, 'disapproved_by')->withTrashed();
    }


    /*
    |--------------------------------------------------------------------------
    | @Methods
    |--------------------------------------------------------------------------
    */
    
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'requester' => $this->requester->renderFullName(),
            'approved_by' => $this->distributor ? $this->distributor->renderName() : '',
            'disapproved_by' => $this->disapprover ? $this->disapprover->renderName() : '',
            'reason' => $this->reason
        ];
    }

    /**
     * Update status of specified request
     * 
     * @param  string $action
     * @param  Array $request
     */
    public function updateStatus($action, $request)
    {
        if($action == 'approve') {
            $this->setToApproved($request);
        }

        if($action == 'reject') {
            $this->setToRejected($request);            
        }
    }

    /**
     * Set request to approved
     * 
     * @param array $request
     */
    public function setToApproved($request)
    {

        $admin = Auth::guard('admin')->user();        
        $vars = [
            'distribute_by' => $admin->id,
            'claimed_at' => now()
        ];

        /** Update request */
        $this->update($vars);

        $voucher = Voucher::find($request->voucher_id);
        $voucherVars = [
            'request_claim_referrals_id' => $this->id,
            'user_id' => $this->requester->id,
            'voucher_id' => $voucher->id,
            'name' => $voucher->name,
            'code' => $voucher->code,
            'type' => $voucher->type,
            'discount' => $voucher->discount,
            'expired_at' => Carbon::now()->addDays($voucher->valid_days),
            'max_usage' => $voucher->max_usage
        ];

        /** Create user vouchers */
        $myVoucher = $this->requester->myVouchers()->create($voucherVars);

        $subject = 'Your request claim referral has been approved!';
        $message = 'Your request claim referral has been successfully approved. Your voucher code: ' . $myVoucher->code;

        $this->requester->notify(new RequestClaimReferralNotification($subject, $message));

    }

    /**
     * Set request to reject
     * 
     * @param array $request
     */
    public function setToRejected($request) 
    {
        $admin = Auth::guard('admin')->user();        
        $vars = [
            'disapproved_by' => $admin->id,
            'disapproved_at' => now(),
            'reason' => $request->reason
        ];

        /** Update request */
        $this->update($vars);        

        $subject = 'Your request claim referral has been rejected!';
        $message = "We're sorry to hear that your request claim referral has been disapproved.";

        $this->requester->notify(new RequestClaimReferralNotification($subject, $message, $request->reason));        
    }

    /*
    |--------------------------------------------------------------------------
    | @Renders
    |--------------------------------------------------------------------------
    */

    /**
     * Render request status
     * 
     * @return string
     */
    public function renderStatus()
    {   
        /** If request is disapproved */
        if($this->disapproved_by) {
            return 'Rejected';
        }

        /** If request is approved */
        if($this->distribute_by) {
            return 'Approved';
        }

        return 'Pending';
    }

    /**
     * Render disapprover of specified request
     * 
     * @return string
     */
    public function renderDisapprover()
    {
        if($this->disapproved_by) {
            return $this->disapprover->renderFullName();
        }

        return 'N/A';
    }

    /**
     * Render distributor of specified request
     * 
     * @return string
     */
    public function renderDistributor()
    {
        if($this->distribute_by) {
            return $this->distributor->renderFullName();
        }

        return 'N/A';
    }

    /**
     * Render claimed at of specified request
     * 
     * @return date and time
     */
    public function renderClaimedAt()
    {
        if($this->claimed_at) {
            return $this->claimed_at;
        }

        return 'N/A';
    }

    /**
     * Render reason of specified request
     * 
     * @return String
     */
    public function renderReason()
    {
        if($this->reason) {
            return $this->reason;
        }
        return 'N/A';
    }

    /**
     * Render archive url of specified request
     * 
     * @return string
     */
    public function renderArchiveUrl() 
    {
        return route('admin.request-claim-referrals.archive', $this->id);
    }

    /**
     * Render restore url of specified request
     * 
     * @return String
     */
    public function renderRestoreUrl() 
    {
        return route('admin.request-claim-referrals.restore', $this->id);
    }

    /**
     * Render approve url of specified request
     * 
     * @return string
     */
    public function renderApproveUrl() 
    {
        return route('admin.request-claim-referrals.approve', [$this->id, 'approve']);
    }

    /**
     * Render reject url of specified request
     * 
     * @return string
     */
    public function renderRejectUrl() 
    {
        return route('admin.request-claim-referrals.reject', [$this->id, 'reject']);
    }

    /**
     * Render submit url of specified request
     * 
     * @param  int $id
     * @param  string $action
     */
    public function renderSubmitUrl($id, $action)
    {
        return route('admin.request-claim-referrals.submit', [$id, $action]);
    }

    /*
    |--------------------------------------------------------------------------
    | @Checkers
    |--------------------------------------------------------------------------
    */
}
