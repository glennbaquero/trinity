<?php

namespace App\Http\Controllers\API\Doctor;

use App\Services\GiftAwayService;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Users\Admin;

use App\Notifications\Doctor\RedeemRewardRequest;
use Notification;

class RewardController extends Controller
{
    /**
     * Processe redeeming of rewards
     * 
     * @return json $response
     */
    public function redeem(Request $request)
    {
        $admin = Admin::first();
        $doctor = $request->user();
        
        if($doctor->points->sum('points') < collect($request->selectedSponsorships)->sum('points')) {
            abort(422, 'You have not met the required points to redeem this reward');
        }

        $redeemed = $doctor->redeem()->create([
            'reward_id' => $request->id,
            'data' => json_encode($request->all()),
            'used_points' => collect($request->selectedSponsorships)->sum('points'),
            'sponsorships' => json_encode($request->selectedSponsorships),
        ]);

        Notification::send($admin, new RedeemRewardRequest($request,$doctor));
        
        #WIP motification

        return response()->json([
            'redeemed' => [$redeemed],
        ]);
    }
}
