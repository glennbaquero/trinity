<?php

namespace App\Http\Controllers\API\Care;

use App\Services\GiftAwayService;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Redemptions\Redemption;

use DB;

class RewardController extends Controller
{
    protected $giftaway;

    /**
     * Create new controller instance
     * 
     * @return void
     */
    public function __construct(GiftAwayService $giftaway)
    {
        $this->giftaway = $giftaway;
    }

    /**
     * Fetch all giftaway merchants
     * 
     * @return json $response 
     */
    public function merchants(Request $request)
    {
        return response()->json([
            'merchants' => $this->giftaway->fetchMerchants($request->categoryid),
            'categories' => $this->giftaway->merchantCategories()
        ]);
    }

    /**
     * Show merchant details with reward items
     * 
     * @return json $merchant
     */
    public function details(Request $request)
    {
        return response()->json([
            'merchant' => $this->giftaway->merchantDetails($request->merchant_id)
        ]);
    }

    /**
     * Redeeming of rewards
     * 
     * @return json $merchant
     */
    public function redeem(Request $request)
    {
        $message = null;
        $data = null;
        $error = false;

        if($request->points >= $request->denomination_value) {
            DB::beginTransaction();
                $data = $this->giftaway->byDenomination($request->user(), $request->denominationid, $request->denomination_value, $request->quantity);
                $request->user()->redemptions()->create([
                    'merchant_id' => $request->merchant_id,
                    'denomination_id' => $request->denominationid,
                    'reference_no' => $data->reference_no,
                    'value' => $request->denomination_value,
                    'image_path' => $request->image_path,
                    'merchant_name' => $request->merchant_name,
                    'denomination_name' => $request->denomination_name,
                    'price' => $request->price,
                    'urls' => json_encode($data->urls),
                    'credits_used' => $data->credits_used
                ]);
                $nega_points = $request->denomination_value * -1;
                $request->user()->points()->create(['points' => $nega_points]);

                $message = 'Rewards redeemed! Please check your email to see the details';
            DB::commit();
        } else {
            $message = 'Insufficient points.';
            $error = true;
        }

        $points = $request->user()->points;
        $total_points = collect($points)->reduce(function($num, $point) {
            $num += $point['points'];
            return $num;
        });
        

        return response()->json([
            'message' => $message,
            'error' => $error,
            'data' => $data,
            'points' => $total_points
        ]);
    }

    /**
     * Redeemed Reward History
     * 
     * @return json $merchant
     */
    public function history(Request $request)
    {
        DB::beginTransaction();
            $redeems = $request->user()->redemptions;
        DB::commit();

        return response()->json([
            'redeems' => $redeems,
            'response' => 200
        ]);
    }
}
