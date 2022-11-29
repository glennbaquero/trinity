<?php

namespace App\Http\Controllers\API\Team;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Intervention\Image\Facades\Image;

use App\Models\Users\Doctor;
use App\Models\Regions\Region;
use App\Models\Specializations\Specialization;
use App\Models\MedRepTargets\MedRepTarget;
use App\Models\Announcements\Announcement;

use App\Models\Calls\Call;

use Carbon\Carbon;
use DB;

class ConfigFetchController extends Controller
{
    /**
     * Fetch the system.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch()
    {
        return response()->json([
            'versions' => [
                'ios' => [
                    'stable_version' => config('mobile.ios.stable_version'),
                    'minimum_version' => config('mobile.ios.minimum_version'),
                    'message' => 'Your App is outdated please download latest version! Please download the latest version <a href="https://play.google.com/store/apps/details?id=com.android.chrome" class="white--text" target="_blank">here</a>',
                ],
                'android' => [
                    'stable_version' => config('mobile.android.stable_version'),
                    'minimum_version' => config('mobile.android.minimum_version'),
                    'message' => 'Your App is outdated please download latest version! Please download the latest version <a href="https://play.google.com/store/apps/details?id=com.android.chrome" class="white--text" target="_blank">here</a>',
                ],
            ],
            'routes' => $this->getRoutes(),
        ]);
    }

    protected function getRoutes()
    {
        return array_merge($this->getApiRoutes());
    }

    protected function getApiRoutes() {
        return [

            /** Sync Data */
            'api.team.sync.data' => route('api.team.sync'),                

            /** Login */
            'api.team.login' => route('api.team.login'),
            
            /** Call Plans */
            'api.team.fetch.config.storable' => route('api.team.fetch.config.storable'),
            'api.team.call-plan.fetch' => route('api.team.call-plan.fetch'),
            'api.team.call-plan.store' => route('api.team.call-plan.store'),
            'api.team.call-plan.edit' => route('api.team.call-plan.edit'),
            'api.team.call-plan.remove' => route('api.team.call-plan.remove'),

            'api.team.medrep.reports' => route('api.team.medrep.reports'),
            'api.team.medrep.location.store' => route('api.team.medrep.location.store'),

            /** Profile */
            'api.team.update.profile' => route('api.team.update.profile'),
            'api.team.update.image' => route('api.team.update.image'),
            'api.team.update.password' => route('api.team.update.password'),

            /** Doctors */
            'api.team.doctors.store' => route('api.team.doctors.store'),
        ];
    }

    /**
     * Get resource for Team App
     * 
     */
    protected function getResources(Request $request)
    {

        $now = Carbon::now();

        $doctors = Doctor::formatDoctorsItems($request);
        $specializations = Specialization::get();
        $regions = Region::get();
        
        $calls = $request->user()->calls()
                ->with('doctor','callAttachments')
                ->where(\DB::raw('year(scheduled_date)'), $now->format('Y'))
                ->where('status', '!=', Call::REJECTED)
                ->get();


        $requestArray = new Request([
            'sortType' => 1,
            'filter' => $now->month,
            'year' => $now->year,
        ]);
        
        $reports = MedRepTarget::formatReport($requestArray, $request->user()->id);
        $announcements = Announcement::fetch(Announcement::APP_TEAM);

        return response()->json([
            'doctors' => $doctors,
            'specializations' => $specializations,
            'regions' => $regions,
            'calls' => $calls,
            'reports' => $reports,
            'announcements' => $announcements
        ]);
    }

    protected function syncData(Request $request)
    {
        $signature;

        /** Start transaction */
        DB::beginTransaction();

            if($request->type == 'call') {
                foreach ($request->data as $data) {
                    $call = Call::find($data['id']);

                    $vars = [
                        'medical_representative_id' => $data['medical_representative_id'],
                        'doctor_id' => $data['doctor_id'],
                        'agenda' => $data['agenda'],
                        'arrived_at' => $data['arrived_at'],
                        'left_at' => $data['left_at'],
                        'notes' => $data['notes'],
                    ];

                    /*
                     * Create or update call
                     */
                    if($data['type'] == 'create') {
                        $vars['clinic'] = $data['clinic'];
                        $vars['scheduled_date'] = $data['scheduled_date'];
                        $call = Call::create($vars);
                    } else {

                        $call->update($vars);                        
                    
                    }

                    if($data['signature']) {
                        /**
                         * Save signature
                         */
                        $call->callAttachments()->updateOrCreate([
                            'type' => 2,
                            'name' => 'signature',
                            'file_path' => $this->encodeBase64($data['signature']),
                        ]);                        
                    }

                }
            }

            if($request->type == 'attachments') {
                foreach ($request->data as $data) {
                    $call = Call::find($data['call_id']);

                    /**
                     * Save attachments
                     */
                    $call->callAttachments()->updateOrCreate([
                        'type' => 3,
                        'name' => $data['filename'],
                        'file_path' => $this->encodeBase64($data['file'], $data['filename']),
                    ]);
                }
            }


        /** End transaction */
        DB::commit();

        return response()->json([
            'message' => 'Succesfully sync',
        ]);
    }

    public function encodeBase64($base64, $filename = null)
    {   
        $extension = 'png';
        $replaceableString = explode(',', $base64); 
        
        if($filename) {
            $extension = explode('.', $filename);
            $extension = $extension[1];
        }

        $base64 = str_replace($replaceableString[0] . ',' , '', $base64);
        $base64 = str_replace(' ', '+', $base64);
        $base64 = base64_decode($base64);

        $optimized_image = Image::make($base64)->encode($extension);
        $width = $optimized_image->getWidth();
        $height = $optimized_image->getHeight();

        $optimized_image->fit(300, 300);

        $file_path = 'public/call-attachments/'. str_random(10). '.' . $extension;
        \Storage::put($file_path, $optimized_image);

        return $file_path;
    }
}
