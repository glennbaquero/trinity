<?php

namespace App\Http\Controllers\Admin\Redeems;

use App\Models\Rewards\Redeem;
use App\Models\Rewards\Reward;
use App\Models\Users\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\PushService;
use App\Services\PushServiceDoctor;
use App\Notifications\Doctor\ApproveRedeem;
use App\Notifications\Doctor\RejectRedeem;
use Notification;

class RedeemController extends Controller
{
    public function index() {
        return view('admin.redeems.index');
    }
    /**
    * Approve a specific call
    *
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function approve(int $id)
    {
        $redeem = Redeem::find($id);
        $redeem->update(['status' => 1]);

        $doctor = Doctor::find($redeem->doctor_id);
        $doctor->points()->create(['points' => $redeem->used_points*'-1']);

        $reward = Reward::find($redeem->reward_id);
        
        $push = new PushServiceDoctor('Request to redeem status update', 'Your request to redeem ' . $reward->name . ' has been approved');
        $push->pushToOne($doctor);

        Notification::send($doctor, new ApproveRedeem($reward));

        return response()->json([
            'message' => "You have successfully approved Redemption #{$id}",
        ]);
    }

    /**
    * Reject a specific call
    *
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function reject(int $id)
    {
        $redeem = Redeem::find($id);
        $redeem->update(['status' => 2]);

        $doctor = Doctor::find($redeem->doctor_id);
        $reward = Reward::find($redeem->reward_id);

        $push = new PushServiceDoctor('Request to redeem status update', 'Your request to redeem ' . $reward->name . ' has been rejected');
        $push->pushToOne($doctor);

        Notification::send($doctor, new RejectRedeem($reward));

        return response()->json([
            'message' => "You have successfully rejected Redemption #{$id}",
        ]);
    }
}
