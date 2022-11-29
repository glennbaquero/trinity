<?php

namespace App\Models\Users;

use App\Models\Addresses\Address;
use App\Models\Articles\Comment;
use App\Models\Articles\Reply;
use App\Models\Points\Point;
use App\Models\Products\Product;
use App\Models\Products\ProductParent;
use App\Models\Users\Doctor;
use App\Models\Users\UserSetting;
use App\Models\Invoices\Invoice;
use App\Models\Invoices\InvoiceItem;
use App\Models\Carts\Cart;
use App\Models\Redemptions\Redemption;
use App\Models\Prescriptions\Prescription;
use App\Models\Files\File;
use App\Models\Refunds\Refund;
use App\Models\Sessions\VideoCallSession;

use App\Notifications\Care\UserSuccessfulReferral;

use App\Models\BloodPressures\BloodPressure;
use App\Models\BloodSugars\BloodSugar;
use App\Models\HeartRates\HeartRate;
use App\Models\Bmis\Bmi;
use App\Models\Cholesterols\Cholesterol;
use App\Models\Consultations\Consultation;
use App\Models\CreditInvoices\CreditInvoice;
use App\Models\PersonalInformations\PersonalInformation;
use App\Models\ConsultationChats\ConsultationChat;
use App\Models\Vouchers\UserVoucher;
use App\Models\Referrals\Referral;
use App\Models\Referrals\SuccessReferral;
use App\Models\Reviews\DoctorReview;
use App\Models\Referrals\RequestClaimReferral;

use App\Models\Notifications\DeviceToken;

use App\Services\PushService;
use App\Services\PushServiceDoctor;

use App\Traits\CreditTrait;

use App\Notifications\Doctor\ReviewerNotification;
use Notification;

use App\Extendables\BaseUser as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\Web\Auth\ResetPassword;
use App\Notifications\Web\Auth\VerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Password;

use App\Traits\FileTrait;
use App\Helpers\StringHelpers;
use Carbon\Carbon;

use Laravel\Scout\Searchable;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use FileTrait;
    use Searchable;
    use CreditTrait;

    const APPROVED = 1;
    const REJECTED = 0;

    /** User type */
    const PATIENT = 0;
    const SECRETARY = 1;

    protected $fillable = [
		'type', 'first_name', 'last_name', 'email', 'mobile_number', 'image_path', 'password', 'email_verified_at',
        'apple_id',
    ];

    protected $appends = ['fullname', 'reward_points', 'full_image', 'purchased_products', 'share_status'];
    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
        // return $this->hasMany(Comment::class);
    }

    public function replies()
    {
        return $this->morphMany(Reply::class, 'replyable');
        // return $this->hasMany(Comment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class)->withTrashed();
    }

    public function points()
    {
        return $this->morphMany(Point::class, 'pointable');
    }

    public function redemptions()
    {
        return $this->hasMany(Redemption::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_user', 'user_id', 'doctor_id');
    }

    public function deviceTokens()
    {
        return $this->morphMany(DeviceToken::class, 'deviceable');
    }
    
    public function products()
    {
        return $this->belongsToMany(ProductParent::class, 'product_user', 'user_id', 'product_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function reviewers()
    {
        return $this->belongsToMany(Doctor::class, 'patient_reviewers', 'user_id', 'doctor_id');
    }

    public function purchased()
    {
        return $this->belongsToMany(Doctor::class, 'purchased_doctors', 'doctor_id', 'user_id');
    }

    public function blood_pressures()
    {
        return $this->hasMany(BloodPressure::class);
    }

    public function blood_sugars()
    {
        return $this->hasMany(BloodSugar::class);
    }

    public function heart_rates()
    {
        return $this->hasMany(HeartRate::class);
    }

    public function bmis()
    {
        return $this->hasMany(Bmi::class);
    }

    public function cholesterols()
    {
        return $this->hasMany(Cholesterol::class);
    }
    
    public function user_setting()
    {
        return $this->hasOne(UserSetting::class);
    }

    public function consultations() 
    {
        return $this->hasMany(Consultation::class, 'user_id')->withTrashed();
    }

    public function credit_invoices()
    {
        return $this->hasMany(CreditInvoice::class);
    }

    public function personal_information()
    {
        return $this->hasOne(PersonalInformation::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class, 'user_id')->withTrashed();
    }
    
    public function videoCallSessionDispatchable()
    {
        return $this->morphMany(VideoCallSession::class, 'dispatchable');
    }

    public function videoCallSessionReceivable()
    {
        return $this->morphMany(VideoCallSession::class, 'receivable');
    }

    public function myVouchers() 
    {
        return $this->hasMany(UserVoucher::class);
    }

    public function referred()
    {
        return $this->belongsToMany(User::class,'referrals','referrer_id','referee_id');
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referral()
    {
        return $this->hasOne(Referral::class, 'referee_id');
    }

    public function successReferrals()
    {
        return $this->hasMany(SuccessReferral::class, 'referrer_id');
    }

    public function reviews()
    {
        return $this->hasMany(DoctorReview::class);
    }

    public function requestClaims()
    {
        return $this->hasMany(RequestClaimReferral::class, 'request_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function sendPasswordResetNotification($token) {
        $this->notify(new ResetPassword($token));
    }

    public function sendEmailVerificationNotification() {
        $this->notify(new VerifyEmail);
    }

    public function broker() {
        return Password::broker('users');
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name
        ];
    }


    /**
     * Creation of success referral for specified user
     * 
     * @param  int $invoiceID
     */
    public function successfulReferral($invoiceID) 
    {
        $referral = $this->referral;

        if($referral) {
            $referrer = $referral->referrer;
            $successReferral = $referrer->successReferrals()->create([
                'referee_id' => $this->id,
                'invoice_id' => $invoiceID
            ]);
            $referrer->notify(new UserSuccessfulReferral($this, $successReferral));
        }
    }

    /**
     *  Add referral code 
     * 
     */
    public function addReferralCode()
    {
         $this->referral_code = $this->referral_code ?  $this->referral_code : $this->getReferralCode();
         return $this->save();
    }


    /**
     * Get and Update all the user dont have referral code
     * 
     */
    public static function distributeReferralCode() {

        $users = User::where('referral_code', null)->withTrashed()->get();

        foreach ($users as $user) {
            $user->addReferralCode();
        }

        return 'success';
    }
    /**
     * Get referral Code
     *
     * Default Format : (trinity + user initial + user id)
     * Alt Format : (trinity + email first 2 letters + user id)
     */
    public function getReferralCode()
    {   
        $words = explode(' ',$this->renderFullName());
        
        // Check if the user full name using english alphabeth
        if(!preg_match('/[^A-Za-z0-9 ]+/', $this->renderFullName())) {
            return 'TRINITY' .  strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1)) . $this->id;
        } else {
            return 'TRINITY' .  strtoupper(substr($this->email, 0, 1). substr($this->email, 1, 1)) . $this->id;
        }  
    }
    
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

    public function getCart() 
    {
        $cart = $this->carts->where('completed', 0)->first();

        if($cart) {
            $cartItems = collect($cart->cartItems()->get())->map(function($item) {
                $item->prescription = $item->hasPrescription();
                $item->consultation_data = $item->getConsultation();
                $item->linked_md = $item->getLinkedMD();
                $item->product_name = $item->product->name;
                $item->product_price = $item->product->price;
                $item->prescription_path = $item->prescription_path ? url($item->renderImagePath('prescription_path')) : '';
                $item->image_path = url($item->product->renderImagePath());
                $item->total_per_item = number_format($item->product->price * $item->quantity, 2);
                $item->max = $item->quantity >= $item->product->inventory->stocks;
                $item->stocks = $item->product ? $item->product->renderStocks() : 0;          
                return $item;
            })->values();
            return $cartItems;
        }
        return [];
    }

    public function getOrCreateCart() 
    {
        $cart = $this->carts->where('completed', 0)->first();

        return $cart ?? $this->carts()->create(['user_id' => $this->id]);
    }

    /**
     * Append rewards points
     * 
     * @return integer $points
     */
    public function getRewardPointsAttribute()
    {
        return $this->points->sum('points');
    }

    /**
     * Append image full path
     * 
     * @return string $image
     */
    public function getFullImageAttribute()
    {
        return asset('storage/' . $this->image_path);
    }

    /**
     * Get fullname attribute
     */
    public function getFullnameAttribute()
    {
        return $this->renderFullName();
    }

    /**
     * Get share status attribute
     * 
     */
    public function getShareStatusAttribute()
    {   
        if($this->user_setting) {
            if($this->user_setting->share_records) {
               return true;
            }            
        }

    }

    /**
     * Get user most purchased product per doctor
     * 
     * @param  Object $doctor
     */
    public function getMostPurchasedProduct($doctor) 
    {

        return $this->invoices->flatMap(function($invoice) {
                $ids = $this->doctors->map(function($doctor) {
                    return $doctor->specialization->products->pluck('id');
                })->flatten();

                return $invoice->invoiceItems->whereIn('product_id', $ids);
            })
            ->flatten(1)->groupBy('product_id')->map(function($item) {
                $data = json_decode($item[0]['data']);
                return [
                    'id' => $item[0]['product_id'],
                    'sold' => $item->sum('quantity'),
                    'name' => $data->name
                ];
            })->sortBy('quantity')->values();
    }
    
    /**
     * Get purchased products by doctor
     */
    public function getPurchasedProductsAttribute()
    {
        $user = request()->user();

        return  $this->invoices->flatMap(function($invoice) use($user) {
                    $ids = $this->doctors->map(function($doctor) {
                        return $doctor->specialization->products->pluck('id');
                    })->flatten();

                    return $invoice->invoiceItems->whereIn('product_id', $ids);
                })
                ->map(function($item) {
                    $data = json_decode($item->data);
                    return [
                        'product_id' => $item->product_id,
                        'name' => $data->brand_name,
                        'points' => $data->client_points * $item->quantity,
                        'image_path' => $data->full_image ?? '',
                        'date_purchased' => $item->created_at->toDateString()
                    ];
                });
    }


    /**
     * Send inapp notification and push notification
     * 
     * @param  array $oldReviewers
     * @param  array $newReviewers
     */
    public function sendReviewerNotification($oldReviewers, $newReviewers) 
    {
        $recipients = array_diff($newReviewers, $oldReviewers);

        foreach ($recipients as $key => $doctor) {
            $doctor = Doctor::find($doctor);

            $message = "You've been allowed to view the patient records of " . $this->renderFullName();

            /** Send notification */
            Notification::send($doctor, new ReviewerNotification($message));
            

            $push = new PushServiceDoctor('MyHealth Records', $message);
            $push->pushToOne($doctor);

        }   

    }

    public function getPatients() 
    {
        $ids = [];
        $doctors = $this->doctors;

        foreach ($doctors as $key => $doctor) {
            foreach ($doctor->patients as $key => $patient) {
                $ids[] = $patient->id;
            }

        }

        return $ids;

    }

    /*
    |--------------------------------------------------------------------------
    | Renders
    |--------------------------------------------------------------------------
    */

    /**
     * Render points per doctor
     * 
     * @param  object $doctor
     */
    public function renderPointsPerDoctor($doctor)
    {
        $points = 0;

        foreach ($this->invoices as $key => $invoice) {
            foreach ($invoice->invoiceItems as $key => $item) {
                $product = json_decode($item->data);
                      
                if($product->specialization_id === $doctor->specialization_id) {
                    $clientPoints = $product->client_points * $item->quantity;

                    $points += $clientPoints;
                }

            }
        }

        return $points;
    }

    /**
     * Render full name of specified resource from storage
     * 
     * @return String
     */
    public function renderFullName()
    {
        return $this->first_name .' '. $this->last_name;
    }

    /**
     * Render name for specific user
     * 
     * @return string/route
     */
    public function renderName() 
    {
        return $this->first_name. " " .$this->last_name;
    }

 
    public function renderStatus() {
        return [
            'value' => $this->approved,
            'text' => $this->approved === null ? 'Pending' : ($this->approved ? 'Approved' : 'Rejected')
        ];
    }

    public function renderShowUrl($prefix = 'admin') {
        if (in_array($prefix, ['web'])) {
            return route(StringHelpers::addRoutePrefix($prefix) . 'profiles.show');
        }
        
        return route($prefix . '.users.show', $this->id);
    }

    public function renderArchiveUrl($prefix = 'admin') {
        if (in_array($prefix, ['web'])) {
            return;
        }

        return route($prefix . '.users.archive', $this->id);
    }

    public function renderRestoreUrl($prefix = 'admin') {
        if (in_array($prefix, ['web'])) {
            return;
        }

        return route($prefix . '.users.restore', $this->id);
    }

    /**
     * Render manage credits url
     * 
     */
    public function renderManageCreditsUrl()
    {
        return route('admin.users.manage-credits', $this->id);
    }

    /**
     * Rendering user types
     * 
     * @return  array of types
     */
    public static function renderUserTypes()
    {
        return [
            [ 'label' => 'Patient', 'value' => self::PATIENT ],
            [ 'label' => 'Secretary', 'value' => self::SECRETARY ],
        ];
    }

    /**
     * Render user type
     * 
     */
    public function renderUserType()
    {
        if($this->isPatient()) {
            return 'Patient';
        }
        return 'Secretary';
    }


    /*
    |--------------------------------------------------------------------------
    | Checkers
    |--------------------------------------------------------------------------
    */
    
    /**
     * Check if account type is patient
     * 
     * @return boolean
     */
    public function isPatient()
    {
        if($this->type === self::PATIENT) {
            return true;
        }
    }

    /**
     * Check if user is a first time buyer
     * 
     * @param  object $doctor
     */
    public function firstTimeBuyer($doctor = null)
    {   

        if($doctor) {
            if($this->purchased()->where('id', $doctor->id)->exists()) {
                return false;
            }            
        } else {
            $invoiceCount = $this->invoices->where('completed', 1)->count();
            if($invoiceCount > 1) {
                return false;
            } else {
                return true;
            }
        }

        return true;
    }

    /**
     * Check if already requested 
     * 
     * @param  int $referralID
     * @return boolean 
     */
    public function checkIfAlreadyRequested($referralID)
    {
        $request = $this->requestClaims()->where(['success_referral_id' => $referralID, ['disapproved_by' ,'=', null]])->first();
        if($request) {
            if($request->disapproved_by) {
                return false;
            }
            return true;
        }
        return false;
    }
    
    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */
   
    /**
     * Get user pending vouchers
     * 
     * @return collection of pending vouchers
     */
    public function getPendingRequestVouchers()
    {
        $id = $this->id;

        $result = RequestClaimReferral::where('request_by',$id)
            ->whereNull('claimed_at')->whereNull('disapproved_at')->get();

        return $result;

    }

    /**
     * Get user active vouchers
     * 
     * @return collection of pending vouchers
     */
    public function getActiveVouchers()
    {
        $id = $this->id;

        $result = UserVoucher::where('user_id',$id)
            ->whereDate('expired_at','>', Carbon::now())->get();

        return $result;
        
    }

}