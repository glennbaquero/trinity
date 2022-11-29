<?php

namespace App\Models\Users;

use App\Models\Calls\Call;
use App\Models\Users\Doctor;
use App\Models\Invoices\Invoice;
use App\Models\Calls\TargetCall;
use App\Models\Regions\Region;
use App\Models\MedRepTargets\MedRepTarget;
use App\Models\MedRepLocationLogs\MedRepLocationLog;

use App\Notifications\Team\ResetPassword;
use App\Notifications\Web\Auth\VerifyEmail;
use App\Extendables\BaseUser as Authenticatable;

use Password;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Support\Facades\Hash;
use App\Helpers\StringHelpers;
use App\Traits\FileTrait;

class MedicalRepresentative extends Authenticatable implements MustVerifyEmail, JWTSubject
{

    protected $fillable = ['region_id', 'email', 'fullname', 'mobile', 'password', 'image_path'];

    use FileTrait;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function calls()
    {
        return $this->hasMany(Call::class);
    }

    public function targetCalls()
    {
        return $this->hasMany(TargetCall::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class)->withTrashed();
    }

    public function targets()
    {
        return $this->hasMany(MedRepTarget::class);
    }

    public function locationLog()
    {  
        return $this->hasOne(MedRepLocationLog::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Renders
    |--------------------------------------------------------------------------
    */
    public function renderShowUrl($prefix = 'admin')
    {
        return route(StringHelpers::addRoutePrefix($prefix) . 'medreps.show', $this->id);
    }

    public function renderArchiveUrl()
    {
        return route('admin.medreps.archive', $this->id);
    }

    public function renderRestoreUrl()
    {
        return route('admin.medreps.restore', $this->id);
    }

    public function renderShortTitle() {
        return substr($this->fullname, 0, 7) . '...';
    }


    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getCalls()
    {
        $calls = $this->calls->where('status', '1')->map(function($call) {
            $c = collect($call->with('doctor')->find($call->doctor_id))->except(['medical_representative_id', 'deleted_at', 'created_at', 'updated_at']);

            return $c->all();
        });

        return $calls;
    }

    public function getProfile()
    {
        $profile = collect($this)->except('region_id');
        $profile['region'] = $this->region;
        
        return $profile->all();
    }

    public function getDoctors()
    {
        $doctors = $this->doctors->map(function($doctor) {
            $d = collect($doctor)->except(['specialization_id', 'medical_representative_id', 'email_verified_at', 'updated_at', 'deleted_at']);
            $d['specialization'] = collect($doctor->specialization)->only(['id', 'name']);

            return $d->all();
        });

        return $doctors->all();
    }

    /**
     * Overrides default reset password notification
     */
    public function sendPasswordResetNotification($token) {
        $this->notify(new ResetPassword($token));
    }

    public function sendEmailVerificationNotification() {
        $this->notify(new VerifyEmail);
    }

    public function broker() {
        return Password::broker('med_reps');
    }

    public static function store($request, $item = null, $columns = ['region_id', 'email', 'fullname', 'mobile'], $appUser = false)
    {

        $vars = $request->only($columns);

        if (!$item) {
            $vars['password'] = uniqid();
            $item = static::create($vars);

            $item->sendEmailVerificationNotification();
            $broker = $item->broker();
            $broker->sendResetLink($request->only('email'));
        }
        else {
            $item->update($vars);
        }
        
        return $item;
    }

    /**
     * Get patients id that connected to Medical rep
     * via doctors
     * 
     * @return array
     */
    public function getPatientsIds()
    {

        $patientsIds = [];

        foreach ($this->doctors as $key => $doctor) {
            foreach ($doctor->patients as $key => $patient) {
                if(!in_array($patient->id, $patientsIds)) {
                    array_push($patientsIds, $patient->id);                    
                }

            }
        }
        return $patientsIds;
    }

    /*
    |--------------------------------------------------------------------------
    | @Renders
    |--------------------------------------------------------------------------
    */

    /**
     * Render total sales
     * 
     * @param  array $month
     * @param  string $year 
     */
    public function renderTotalSales($month, $year) 
    {
        $totalSales = 0;

        foreach ($this->doctors as $key => $doctor) {
            $totalSales += $doctor->renderDoctorTotalSales($month, $year);
        }

        return $totalSales; 
    }

    /**
     * Render total patients
     * 
     * @param  array $months
     * @param  string $year
     */
    public function renderTotalPatients($months, $year, $countAll = false, $today = false)
    {
        $totalPatients = 0;

        $invoices = Invoice::getInvoices($months, $year, $today);
        $patientsIds = $this->getPatientsIds();

        foreach ($invoices as $key => $invoice) {
            if(in_array($invoice->user_id, $patientsIds) && !$countAll) {
                $totalPatients++;
            } else  {
                $totalPatients++;
            }

        }
        return $totalPatients;
    }

    /**
     * Render total doctors
     * 
     * @param  array $months
     * @param  string $year
     */
    public function renderTotalDoctors($months, $year)
    {
        $doctors = [];

        $invoices = Invoice::getInvoices($months, $year);

        foreach ($invoices as $key => $invoice) {
            foreach ($invoice->user->doctors as $key => $doctor) {
                if(!in_array($doctor->id, $doctors)) {
                    array_push($doctors, $doctor->id);
                }
            }
        }
        return count($doctors);
    }

}
