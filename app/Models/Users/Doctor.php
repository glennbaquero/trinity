<?php

namespace App\Models\Users;

use App\Models\Calls\Call;
use App\Models\Points\Point;
use App\Models\Users\User;
use App\Models\Articles\Reply;
use App\Models\Redemptions\Redemption;
use App\Models\Rewards\Redeem;
use App\Models\Articles\Comment;
use App\Models\Specializations\Specialization;
use App\Models\Users\MedicalRepresentative;
use App\Models\Payouts\Payout;
use App\Models\Consultations\Consultation;        
use App\Models\Schedules\Schedule;
use App\Models\Refunds\Refund;        
use App\Models\Sessions\VideoCallSession;
use App\Models\Reviews\DoctorReview;
use App\Models\Invoices\InvoiceItem;
use App\Models\Carts\CartItem;

use App\Notifications\Web\Auth\VerifyEmail;
use App\Notifications\Doctor\ResetPassword;
use App\Extendables\BaseUser as Authenticatable;

use App\Models\Notifications\DeviceToken;

use App\Helpers\StringHelpers;
use App\Traits\FileTrait;
use App\Traits\HelperTrait;
use App\Traits\CreditTrait;
use App\Traits\ChatTrait;

use App\Extendables\BaseModel as Model;

use Illuminate\Validation\ValidationException;
use Password;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Scout\Searchable;

use App\Models\Files\File;
use Carbon\Carbon;

class Doctor extends Authenticatable implements MustVerifyEmail, JWTSubject
{
	use FileTrait;
	use HelperTrait;
	use Searchable;
	use CreditTrait;
	use ChatTrait;

	const classA = 1;
	const classB = 2;

	const FOR_UPDATE = 0;
	const APPROVED = 1;
	const REJECTED = 2;

	protected $fillable = [
		'first_name', 'last_name', 'specialization_id', 'medical_representative_id', 'email',
		'class', 'mobile_number', 'clinic_address', 'clinic_hours', 'brand_adaption_notes', 'qr_code_path', 'password','status', 'qr_id', 'consultation_fee',
		'alma_mater', 'place_of_practice', 'license_number', 'signature'
	];

	protected $appends = ['fullname', 'full_image', 'signature_path', 'specialization_name', 'full_qr'];

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
        return Password::broker('doctors');
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

    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
    public function points()
	{
		return $this->morphMany(Point::class, 'pointable');
	}

	// public function redemptions()
	// {
	// 	return $this->morphMany(Redemption::class, 'redemptionable');
	// }
	public function redeem()
	{
		// return $this->hasMany(Redeem::class);
		return $this->hasMany(Redeem::class)->where('status','1');
	}

	public function calls()
	{
		return $this->hasMany(Call::class);
	}

	public function specialization()
	{
		return $this->belongsTo(Specialization::class)->withTrashed();
	}

	public function medicalRepresentative()
	{
		return $this->belongsTo(MedicalRepresentative::class)->withTrashed();
	}

	public function patients()
	{
	    return $this->belongsToMany(User::class, 'doctor_user', 'doctor_id', 'user_id');
	}

	public function secretaries()
	{
	    return $this->belongsToMany(User::class, 'doctor_user', 'doctor_id', 'user_id')->where('type', User::SECRETARY);
	}

    public function deviceTokens()
	{
		return $this->morphMany(DeviceToken::class, 'deviceable');
	}

	public function comments()
	{
	    return $this->morphMany(Comment::class, 'commentable');
	    // return $this->hasMany(Comment::class);
	}

	public function replies()
	{
	    return $this->morphMany(Reply::class, 'replyable');
	}

    public function patient_records()
    {
        return $this->belongsToMany(User::class, 'patient_reviewers', 'doctor_id', 'user_id');
    }

    public function consultations() 
    {
    	return $this->hasMany(Consultation::class, 'doctor_id')->withTrashed();
    }

   	public function payouts()
   	{
   		return $this->hasMany(Payout::class, 'doctor_id')->withTrashed();
   	}

    public function schedules()
    {
    	return $this->hasMany(Schedule::class);
    }
   	public function refunds()
   	{
   		return $this->hasMany(Refund::class, 'doctor_id')->withTrashed();
   	}

    public function videoCallSessionDispatchable()
	{
		return $this->morphMany(VideoCallSession::class, 'dispatchable');
	}

    public function videoCallSessionReceivable()
	{
		return $this->morphMany(VideoCallSession::class, 'receivable');
	}

	public function reviews()
	{
		return $this->hasMany(DoctorReview::class);
	}

	public function invoiceItems()
	{
		return $this->hasMany(InvoiceItem::class);
	}

	public function cartItems()
	{
		return $this->hasMany(CartItem::class);
	}

	/*
	|--------------------------------------------------------------------------
	| Methods
	|--------------------------------------------------------------------------
	*/

		/*
		|--------------------------------------------------------------------------
		| @Phase 5 Methods
		|--------------------------------------------------------------------------
		*/

	    /**
	     * Compute total ratings of specified doctor
	     * 
	     * @return float
	     */
	    public function computeRatings() 
	    {
	        $doctor = $this;
	        $ratings = 0;

	        $totalReviews = $doctor->reviews()->count();
	        if($totalReviews) {
		        $totalRatings = $doctor->reviews()->sum('ratings');
		        $ratings = round($totalRatings / $totalReviews, 1);        	
	        }

	        return $ratings;
	    }

	    /**
	     * Fetch reviews for specified doctor
	     * 
	     * @return array
	     */
	   	public function fetchReviews($offset = 0) 
	   	{	
	   		$list = DoctorReview::where('doctor_id',$this->id);

	   		$count = $list->count();

	   		$reviews = collect($list->offset($offset)->latest()->take(3)->get())->map(function($review) {
				return [
					'reviewer' => $review->reviewer->renderFullName(),
					'reviewer_image' => $review->reviewer->getFullImageAttribute(),
					'ratings' => $review->ratings,
					'created_at' => $review->renderDateOnly(),
					'description' => $review->description,
				];
	   		}); 

	   		return ['reviews' => $reviews, 'count' => $count ];
	   	}



	public function storeSignature($request)
	{
		$vars['signature'] = $request->file('signature')->store('signature', 'public');
		$this->update($vars);
	}

	public function storeProfileImage($request)
	{
		$vars['image_path'] = $request->file('image_path')->store('doctor-images', 'public');
		$this->update($vars);
	}

	/**
	 * Create payout request
	 * 
	 * @param  int $value
	 */
	public function createPayout($value)
	{
		$this->payouts()->create([
			'value' => $value
		]);
	}


	public function toSearchableArray()
	{
		return [
			'id' => $this->id,
			'first_name' => $this->first_name,
			'last_name' => $this->last_name,
			'medical_representative' => $this->medicalRepresentative ? $this->medicalRepresentative->fullname : null,
			'specialization' => $this->specialization->name,
		];
	}

	public static function store($request, $item = null, $columns = [
		'first_name', 'last_name', 'specialization_id', 'medical_representative_id', 'email',
		'class', 'clinic_address', 'clinic_hours', 'brand_adaption_notes', 'consultation_fee',
		'alma_mater', 'place_of_practice', 'license_number'
	], $appUser = false)
    {
        $vars = $request->only($columns);
        $vars['mobile_number'] = $request->filled('mobile_number') ? $request->mobile_number : '# not attach';


        if (!$item) {
        	$vars['password'] = uniqid();
	        $vars['qr_id'] = static::generateRandomString();
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


	public function getAssignProducts() {
        $result = [];


		foreach($this->specialization->products as $product) {

			array_push($result, [
			    'id' => $product->id,
			    'specialization' => $product->specialization,
			    'name' => $product->name,
			    'description' => $product->description,
			    'ingredients' => $product->ingredients,
			    'nutritional_facts' => $product->nutritional_facts,
			    'directions' => $product->directions,
			    'client_points' => $product->renderClientPoints(),
			    'prescriptionable' => $product->prescriptionable,
			    'price' => $product->price,
			    'stocks' => $product->inventory ? $product->inventory->stocks: 0,
			    'bought' => $product->users->where('id', request()->user()->id)->first() ? true : false,
			    'full_image' => url($product->renderImagePath()),
                'is_free_product' => $product->is_free_product
			]);
		}
		return $result;
	}

	/**
	 * Appends fullname
	 *
	 * @return string
	 */
	public function getFullnameAttribute()
	{
		return $this->first_name . ' ' . $this->last_name;
	}

	// /**
	//  * Appends fullname
	//  *
	//  * @return string
	//  */
	// public function getSpecializationNameAttribute()
	// {
	// 	return $this->specialization->name;
	// }

	/**
     * Append image full path
     *
     * @return string $image
     */
    public function getFullImageAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : asset('images/doctor_icon.jpg');
    }

    public function getFullQrAttribute()
    {
        return url('/') . \Storage::url($this->qr_code_path);
    }

    public function getSignaturePathAttribute()
    {
        return $this->signature ? asset('storage/' . $this->signature) : '';
    }

	/**
     * Append specialization name
     *
     * @return string $image
     */
    public function getSpecializationNameAttribute()
    {
        return $this->specialization ? $this->specialization->name : 'No specialization assigned';
    }

	public static function generateRandomString($length = 15, $additionalString = null)
	{
	    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charLength = strlen($characters);

	    $randomString = null;

	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charLength - 1)];
	    }

	    $randomString .= $additionalString;

	    return 'TR'.$randomString;
	}

	/**
	 * Get Most purchased doctors product
	 *
	 * @return mixed
	 */
	public function getMostPurchasedProduct()
	{
		return $this->patients->map(function($patient) {
					return $patient->purchased_products;
				})->flatten(1)->groupBy('product_id')->map(function($group) {
					return [
						'sum_points' => $group->sum('points'),
						'name' => $group[0]['name'],
						'image_path' => $group[0]['image_path'],
						'latest_date_purchase' => max($group->pluck('date_purchased')->toArray()),
					];
				})->sortBy('sum_points')->values();
	}

	/**
	 * Format doctors details for API fetching
	 *
	 * @param  $request
	 * @return array
	 */
    public static function formatDoctorsItems($request)
    {

    	$doctors = Doctor::where('medical_representative_id', $request->user()->id)->get();

    	$result = [];

    	foreach ($doctors as $key => $doctor) {
    		$column = [
    			'points' => $doctor->renderPoints(),
    			'patients' => $doctor->formatPatients(),
    			'recent_visit' => $doctor->recentVisit(),
    		];
    		array_push($result, array_merge($column, [
    			'id' => $doctor->id,
    			'first_name' => $doctor->first_name,
    			'last_name' => $doctor->last_name,
    			'fullname' => $doctor->fullname,
    			'mobile_number' => $doctor->mobile_number,
    			'email' => $doctor->email,
    			'clinic_address' => $doctor->clinic_address,
    			'class' => $doctor->class,
    			'clinic_hours' => $doctor->clinic_hours,
    			'specialization_id' => $doctor->specialization_id,
    			'specialization' => $doctor->specialization,
    		]));
    	}

    	return $result;
    }

    public function formatPatients()
    {

    	$result = [];

    	foreach ($this->patients as $key => $patient) {
    		array_push($result, [
    			'id' => $patient->id,
    			'first_name' => $patient->first_name,
    			'last_name' => $patient->last_name,
    			'points' => $patient->renderPointsPerDoctor($this),
    			'most_purchased' => $patient->getMostPurchasedProduct($this),
    		]);
    	}

    	return $result;

    }

    /**
     * Fetch recent visit
     *
     */
    public function recentVisit()
    {
    	$call = $this->calls()->orderBy('scheduled_date', 'asc')->first();

    	if($call) {
    		return $call->scheduled_date;
    	}
    }

    /**
     * Fetch chat request
     * 
     */
    public function fetchChatRequests()
    {
		$now = Carbon::now();

		$consultations = $this->consultations()->where(['type' => Consultation::CHAT, 'status' => Consultation::PENDING])->where('updated_at', '>', $now->subMinutes(5))->get();
		$consultations = Consultation::dataParser($consultations);
		return $consultations;
    }

    /**
     * Fetch doctors earnings
     * 
     */
    public function fetchEarnings($page)
    {
		$earnings = $this->consultations()->where('paid', true)->orderBy('date', 'desc')->paginate(10, ['*'], 'page', $page);
		$collection = Consultation::dataParser($earnings->getCollection());
		$earnings->setCollection($collection);

    	return $earnings; 
    }


	/*
	|--------------------------------------------------------------------------
	| Renders
	|--------------------------------------------------------------------------
	*/

	public function renderDoctorTotalSales($months, $year)
	{
		$doctor = $this;
		$total = 0;

		foreach ($this->patients as $key => $patient) {

			$invoices = $patient
						->invoices()
						->where('payment_status', 1)
						->whereIn(\DB::raw('month(created_at)'), $months)
                        ->where(\DB::raw('year(created_at)'), $year)
                        ->get();

			foreach ($invoices as $key => $invoice) {
				foreach ($invoice->invoiceItems as $key => $item) {
					if(in_array($item->product_id, $doctor->specialization->products->pluck('id')->toArray())) {
						$total += $item->total_price;
					}
				}
			}

		}
		return $total;
	}


	/**
	 * Render total points of specified doctors
	 *
	 * @return int
	 */
	public function renderPoints()
	{
		if($this->points) {
			return $this->points->sum('points');
		}
		return 0;

	}

	/**
     * Render name for specific doctor
     * 
     * @return string/route
     */
    public function renderName() 
    {
        return $this->first_name. " " .$this->last_name;
    }


	public function renderArchiveUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'doctors.archive', $this->id);
	}

	public function renderRestoreUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'doctors.restore', $this->id);
	}

	public function renderShowUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'doctors.show', $this->id);
	}

	public function renderStatus() {
		foreach (self::getStatus() as $key => $status) {
            if($status['id'] == $this->status) {
                return $status;
            }
        }

        return null;
	}

	public function renderApproveUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'doctors.approve', $this->id);
	}

	public function renderRejectUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'doctors.reject', $this->id);
	}

	public function renderResetPassword() {
		return route('admin.doctors.send.password.reset', $this->id);
	}

	public function renderDownloadQRUrl()
	{
		return route('admin.doctors.download.qr', $this->id);
	}

	public function renderFullName()
	{
		return 'Dr. ' . $this->first_name . ' ' . $this->last_name;
	}

    /**
     * Render manage credits url
     * 
     */
    public function renderManageCreditsUrl()
    {
        return route('admin.doctors.manage-credits', $this->id);
    }

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/
	public function getSpecialization()
	{
		return $this->specialization->name;
	}

	public function getMedRep()
	{
		if($this->medicalRepresentative) {
			return $this->medicalRepresentative->fullname;
		}
		return 'None';
	}

	public function getUserDoctors()
	{
		$doctor = collect($this)->except([
			'medical_representative_id', 'specialization_id', 'email_verified_at',
			'create_at', 'updated_at', 'deleted_at'
		]);

		$doctor['specialization'] = $this->specialization->name;

		return $doctor->all();
	}

	public function getStatus() {
		return [
            ['id' => self::FOR_UPDATE, 'value' => 'For Update'],
            ['id' => self::APPROVED, 'value' => 'Approved'],
            ['id' => self::REJECTED, 'value' => 'Rejected'],
        ];
	}

	/*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/

	/**
	 * Check if current credits >= to value
	 * 
	 * @param  int $value
	 */
	public function checkCredits($value) 
	{
		if($this->countCredits() >= (int) $value) {
			return true;
		} 
	}

}
