<?php

namespace App\Http\Controllers\API\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Articles\Article;
use App\Models\Rewards\Reward;
use App\Models\Rewards\RewardCategory;
use App\Models\Notifications\Notification;
use App\Models\Pages\PageItem;
use App\Models\Pages\Page;
use App\Models\Consultations\Consultation;

use Carbon\Carbon;

use DB;

class HomeController extends Controller
{
    /**
     * Fetch all resources
     * 
     * @return json $response
     */
    public function resource()
    {
        $doctor = request()->user();
        $doctor['qr_path'] = url($doctor->renderImagePath('qr_code_path'));

        $myHealth_item = Page::where('slug', 'doc_app_myhealth')->first();
        
        return response()->json([
            'doctor' => $doctor,
            'articles' => Article::with('comments')->where('for_doctor', 2)->inRandomOrder()->get()->take(5)->toArray(),
            'article_count' => Article::count(),
            'linked_patients' => $doctor->patients()->inRandomOrder()->get()->toArray(),
            'patient_records' => $doctor->patient_records()->pluck('id')->toArray(),
            'patient_count' => $doctor->patients->count(),
            'most_purchased' => $doctor->getMostPurchasedproduct()->toArray(),
            'reward_points' => $doctor->points->sum('points'),
            'reward_categories' => RewardCategory::all(),
            'rewards' => Reward::with('sponsorships')->get()->toArray(),
            'redeemed' => optional($doctor->redeem)->toArray(),
            'myhealth_item' => $myHealth_item->getData(),
            'opentok_key' => env('OPENTOK_API_KEY')            
        ]);
    }
    
    /**
     * Fetch paginated notifications
     * 
     * @return 
     */
    public function notifications()
    {
        $doctor = request()->user();

        return response()->json([
            'data' => $doctor->notifications()->latest()->paginate(10),
        ]);
    }

    /**
     * get the total unread notification
     * 
     * @return 
     */
    public function notificationsCount()
    {
        $doctor = request()->user();

        return response()->json([
            'count' => $doctor->notifications->where('read_at', null)->count(),
        ]);
    }

    /**
     * read the notification
     * 
     * @return 
     */
    public function readNotification(Request $request)
    {
        DB::beginTransaction();
            Notification::find($request->id)->update(['read_at' => Carbon::now()]);
        DB::commit();

        return response()->json([
            'message' => 'success',
        ]);
    }

}
