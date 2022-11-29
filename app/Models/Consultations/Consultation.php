<?php

namespace App\Models\Consultations;

use App\Extendables\BaseModel as Model;

use App\Notifications\Care\CancelConsultation;
use App\Notifications\Care\ConsultationRequest;
use App\Notifications\Doctor\ProcessConsultation;

use App\Services\PushService;
use App\Services\PushServiceDoctor;
use App\Models\Users\Doctor;
use App\Models\Users\User;
use App\Models\Schedules\Schedule;
use App\Models\MedicalPrescriptions\MedicalPrescription;
use App\Models\ConsultationChats\ConsultationChat;
use App\Models\Reviews\DoctorReview;

use Auth;
use Carbon\Carbon;

class Consultation extends Model
{

	protected $dates = ['date'];

    /*
	|--------------------------------------------------------------------------
	| @Consts
	|--------------------------------------------------------------------------
	*/

	const BOOKING = 0;
	const CHAT = 1;

	const PENDING = 0;
	const APPROVED = 1;
	const DISAPPROVED = 2;
	const CANCELLED = 3;
	const REFUNDED = 4;
	const COMPLETED = 5;

	const MAX_CONSULTATION = 4;


	/*
	|--------------------------------------------------------------------------
	| @Attributes
	|--------------------------------------------------------------------------
	*/



	/*
	|--------------------------------------------------------------------------
	| @Relationships
	|--------------------------------------------------------------------------
	*/

	public function schedule()
	{
		return $this->belongsTo(Schedule::class);
	}

	public function doctor()
	{
		return $this->belongsTo(Doctor::class)->withTrashed();
	}

	public function user()
	{
		return $this->belongsTo(User::class)->withTrashed();
	}

	public function prescription()
	{
		return $this->hasOne(MedicalPrescription::class);
	}

	public function chats()
	{
		return $this->hasMany(ConsultationChat::class);
	}

	public function review()
	{
		return $this->hasOne(DoctorReview::class)->withTrashed();
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
			'doctor' => $this->doctor->renderName(),
			'patient' => $this->user->renderFullName()
		];
	}

	/**
     * Store/Update resource to storage
     *
     * @param  array $request
     * @param  object $item
     */
     public static function store($request)
    {
    	$schedule = Schedule::find($request->schedule_id);

        $vars = $request->only(['schedule_id', 'type']);

    	$vars['user_id'] = request()->user()->id;
    	$vars['doctor_id'] = $schedule ? $schedule->doctor_id : $request->doctor_id; 
    	$vars['consultation_number'] = Consultation::generateConsultationNumber();
    	$vars['consultation_fee'] = $schedule ? $schedule->doctor->consultation_fee : $request->fee;
    	$vars['date'] = $schedule ? $schedule->date : now();
    	$vars['start_time'] = $schedule ? $schedule->start_time: null;
    	$vars['end_time'] = $schedule ? $schedule->end_time: null;
    	$vars['status'] = self::PENDING;

        $item = static::create($vars);
        $item->sendConsultationRequestNotif($item->type);

        return $item;
    }

    public function sendConsultationPushNotif()
    {
    	$title = 'Consultation Request';
    	$message = "A chat request from {$this->user->renderName()} with consultation no. {$this->consultation_number} has been sent to your account.";

    	$service = new PushServiceDoctor($title, $message);
    	$service->pushToOne($this->doctor);

    }

    /*Doctor notification if a user has sent a consultation request*/
    public function sendConsultationRequestNotif($type)
    {
    	if($type == self::CHAT) {
	    	$title = 'Consultation Request';
	    	$message = "A chat request from {$this->user->renderName()} with consultation no. {$this->consultation_number} has been sent to your account.";
    	} else {
    		$title = 'Booking Request';
	    	$message = "A booking request from {$this->user->renderName()} with consultation no. {$this->consultation_number} has been sent to your account.";
    	}

		$this->doctor->notify(new ConsultationRequest($title, $message));
    	
    	$service = new PushServiceDoctor($title, $message, $this->user->full_image);
    	$service->pushToOne($this->doctor);

    }

    /*
    * Push notification when either the doctor or the user enters the chat
    */
    public function sendArrivalPushNotif($isUser)
    {
    	if($isUser == 0) {
	    	$title = 'Doctor has Arrived';
	    	$message = "Doctor {$this->doctor->fullname} has now entered the consultation";
	    	
	    	$service = new PushService($title, $message);
	    	$service->pushToOne($this->user);
    	} else {
	    	$title = 'Patient has Arrived';
	    	$message = "Patient {$this->user->renderName()} has now entered the consultation";
	    	
	    	$service = new PushServiceDoctor($title, $message);
	    	$service->pushToOne($this->doctor);
    	} 
    }


    /**
     * Generate consultation number
     * 
     * @return String
     */
    public static function generateConsultationNumber()
    {
        $year = Carbon::parse('now')->year;
        $consultationNumber = 0001;
        $uniqueconsultationNumber = null;

        if ($latestConsultationCount = self::count()) {
            $consultationNumber = $latestConsultationCount + 1;
        }

        /* Create unique invoiceNumber */
        $uniqueconsultationNumber = 'C' . "-" . $year . "-" . sprintf('%04d', $consultationNumber);

        return $uniqueconsultationNumber;
    }


    /**
     * Update consultation status
     * 
     */
	public function updateStatus() 
    {
        $subject = '';
        $this->update([
            'status' => self::CANCELLED
        ]);

        $this->user->notify(new CancelConsultation($this->consultation_number, $this->doctor->fullname));
	}

	/**
	 * Update status
	 * 
	 * @param $status
	 */
	public function setStatus($status, $remaining = null)
	{
		if($status == Consultation::APPROVED) {
			
			if(!$this->paid) {
				/** Process credits */
				$this->user->processCredit('-' . $this->consultation_fee);
				
				/** Process credits */
				$this->doctor->processCredit($this->consultation_fee);				
			}

			if($this->type == Consultation::CHAT) {
				$seconds = $this->remaining ? $this->remaining : 3600; // 60 mins;
				$this->update(['start_time' => Carbon::now()->format('g:i A'), 'remaining' => $seconds, 'status' => $status, 'paid' => true ]);
			} else {
				$this->update(['status' => $status, 'paid' => true]);
				/** Cancelled other  */
				Consultation::where(['schedule_id' => $this->schedule_id, ['id', '!=', $this->id] ])->update(['status' => 2]);
			}


			$this->user->notify(new ProcessConsultation($this, $this->consultation_number, $this->doctor->fullname, 'approved'));
			$this->sendApprovedPushNotif($this->user);
			return;
		}

		if($status == Consultation::DISAPPROVED) {
			$this->sendDisApprovedPushNotif($this->user);			
		}

		if($status == Consultation::COMPLETED) {

			if($this->type == Consultation::CHAT) {
				$this->update(['end_time' => Carbon::now()->format('g:i A'), 'paid' => true, 'remaining' => $remaining]);				
			}

			/** Link doctor */
			if($this->user->doctors) {
				if(!$this->user->doctors()->where(['id' => $this->doctor_id])->exists() && !in_array($status, [Consultation::APPROVED, Consultation::DISAPPROVED])) {
					$this->user->doctors()->attach($this->doctor_id);
				}			
			}

			$this->update(['status' => $status]);

			$this->sendCompletedPushNotif($this->user);
			$this->user->notify(new ProcessConsultation($this, $this->consultation_number, $this->doctor->fullname, 'completed'));
			return;
		}
		$this->update(['status' => $status]);
		$this->user->notify(new ProcessConsultation($this, $this->consultation_number, $this->doctor->fullname, 'disapproved'));
	}

	/**
	 * Send completed push notification
	 * 
	 * @param  object $user
	 */
	public function sendCompletedPushNotif($user)
	{
		$title = "Consultation ended";
		$message = "Your consultation with {$this->doctor->renderName()} has been ended";

		$service = new PushService($title, $message);
		$service->pushToOne($user);
	}


	/**
	 * Send completed push notification
	 * 
	 * @param  object $user
	 */
	public function sendApprovedPushNotif($user)
	{
		if($this->type == self::CHAT) {
			$title = "Consultation Approved";
		} else {
			$title = "Booking Consultation Approved";
		}
		$message = "Your consultation request ( Consultation No: {$this->consultation_number} ) with  Dr. {$this->doctor->renderName()} has been approved";

		$service = new PushService($title, $message);
		$service->pushToOne($user);
	}

	/**
	 * Send completed push notification
	 * 
	 * @param  object $user
	 */
	public function sendDisApprovedPushNotif($user)
	{

		$title = "Consultation Disapproved";
		$message = "Your consultation request with {$this->doctor->renderName()} has been disapproved";

		$service = new PushService($title, $message);
		$service->pushToOne($user);
	}

	/**
	 * Fetch status
	 * 
	 * @param  array  $statuses
	 */
	public static function fetch($column = 'user_id', $statuses = [], $date = null, $type = null , $paginate = false, $page = 1) 
	{
		$user = request()->user(); 
		$consultations = self::where([$column => $user->id])->whereIn('status', $statuses);
		
		if(is_array($type)) {
				$consultations = $consultations->whereIn('type', $type);
		} else {
			if(is_numeric($type)) {
				$consultations = $consultations->where('type', $type);
			}			
		}


		if($date == 'greater-to-now') {
			$consultations = $consultations->whereDate('date', '>=', Carbon::now());
		}

		if($date && $date != 'greater-to-now') {
			$consultations = $consultations->whereDate('date', $date);
		}

		if($paginate) {

			$consultations = $consultations->orderBy('date', 'desc')->paginate(10,['*'],'page',$page);
			$collection = self::dataParser($consultations->getCollection());
			$consultations->setCollection($collection);
			
			return $consultations;
		}

		$consultations = $consultations->orderBy('date', 'desc')->get();

		$consultations = self::dataParser($consultations);

		return $consultations;
	}

	/**
	 * Parsing data
	 * 
	 * @param  array $data
	 */
	public static function dataParser($data)
	{
		$data = collect($data)->map(function($item) {
            return [
                'id' => $item->id,
                'consultation_number' => $item->consultation_number,
                'doctor' => $item->doctor,
                'doctor_name' => $item->doctor->renderName(),
                'doctor_image' => $item->doctor->getFullImageAttribute(),
                'patient' => $item->user,
                'patient_name' => $item->user->renderName(),
                'patient_image' => $item->user->getFullImageAttribute(),
                'date' => $item->date,
                'fee' => number_format($item->consultation_fee, 2, '.', ''),
                'start_time' => $item->start_time,
                'end_time' => $item->end_time,
                'status' => $item->renderStatus(),
                'type' => $item->type,
                'remaining' => $item->remaining,

                'schedule_type' => $item->schedule ? $item->schedule->type : null,
                'refundable' => $item->canRefund(),
                'chat' => $item->canChat(),
                'latest_message' => $item->renderLatestMessage(),
                'latest_message_status' => $item->renderLatestMessageStatus(),                
            ];
		});

		return $data;
	}


	/**
	 * Get statuses
	 * 
	 */
	public static function getStatuses() 
	{
		return [
			['value' => Consultation::PENDING, 'label' => 'Pending'],
			['value' => Consultation::APPROVED, 'label' => 'Approved'],
			['value' => Consultation::DISAPPROVED, 'label' => 'Disapproved'],						
			['value' => Consultation::CANCELLED, 'label' => 'Cancelled'],
			['value' => Consultation::REFUNDED, 'label' => 'Refunded'],
			['value' => Consultation::COMPLETED, 'label' => 'Completed'],			
		];

	}

	/*
	|--------------------------------------------------------------------------
	| @Renders
	|--------------------------------------------------------------------------
	*/


	/**
	 * Render consultation status
	 * 
	 */
	public function renderStatus()
	{
		foreach (self::getStatuses() as $key => $status) {
			if($this->status == $status['value']) {
				return $status['label'];
			}
		}
	}

	/**
	 * Render short date
	 * 
	 */
	public function renderShortDate($date)
	{
		return $date->format('m/d/Y');
	}

	/**
	 * Render consultation type
	 * 
	 */
	public function renderType()
	{

		switch ($this->type) {
			case self::BOOKING:
			return 'BOOKING';
				break;

			case self::CHAT:
			return 'CHAT';
				break;				
		}

	}

	/**
	 * Render schedule date
	 * 
	 */
	public function renderScheduleDate()
	{
		if($this->schedule) {
			return $this->schedule->date->format('m-d-Y') . ' | ' . $this->schedule->start_time . ' ' . $this->schedule->type . ' - ' . $this->schedule->end_time . ' ' .$this->schedule->type;  
		} else {
			return $this->date->format('m-d-Y') . ' | ' . $this->start_time . ' - ' . $this->end_time;
		}
	}

	/**
	 * Render show url
	 * 
	 */
	public function renderShowUrl() 
	{
		return route('admin.consultations.show', [$this->id, $this->consultation_number]);
	}	

	/**
	 * Render latest message
	 * 
	 * @return string
	 */
	public function renderLatestMessage()
	{
		if(count($this->chats)) {
			$latestMessage = $this->chats()->latest()->first();
			$latestMessage = $latestMessage->message != 'null' ? $latestMessage->message : 'Sent a photo';
			return strip_tags($latestMessage);
		}
	}

	/**
	 * Render latest message status
	 * 
	 * @return string
	 */
	public function renderLatestMessageStatus()
	{
		if(count($this->chats)) {
			$latestMessage = $this->chats()->latest()->first();
			if($latestMessage->receiver_id == request()->user()->id) {
				return $latestMessage->read_at;
			}
			return true;
		}
	}

	/*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/

	/**
	 * Check pending booking using doctor_id
	 * this event trigger via Chat Now
	 * 
	 * @param  int $doctorID
	 */
	public static function checkPending($doctorID) 
	{
		$user = request()->user();
		$now = Carbon::now();
		$consultation = Consultation::whereNotIn('status', [Consultation::DISAPPROVED, Consultation::REFUNDED, Consultation::CANCELLED])->where(['doctor_id' => $doctorID, 'user_id' => $user->id, 'type' => Consultation::CHAT]);
		if($consultation->where('remaining', '>', '0')->first()) {
			$consultation = $consultation->latest()->first();
			if($consultation->created_at > Carbon::now()->subDays(1)) {
				if($consultation->status === Consultation::COMPLETED) {
					$consultation->update(['status' => Consultation::PENDING]);
					$consultation->sendConsultationPushNotif($consultation->type);
				}
				if($consultation->updated_at < Carbon::now()->subMinutes(5)) {
					$consultation->update(['updated_at' => now()]);
					$consultation->sendConsultationPushNotif($consultation->type);					
				}
				return $consultation;
			}
			
		} else {
			$consultation = Consultation::where(['doctor_id' => $doctorID, 'user_id' => $user->id, 'type' => Consultation::CHAT])
								->whereIn('status', [Consultation::PENDING, Consultation::APPROVED])->first();			
			if($consultation && $consultation->created_at > Carbon::now()->subDays(1)) {
				if($consultation->updated_at < Carbon::now()->subMinutes(5) && $consultation->status == Consultation::PENDING) {
					$consultation->update(['updated_at' => now()]);
					$consultation->sendConsultationPushNotif($consultation->type);
				}
				return $consultation;
			}
		}

	}

	/**
	 * Check if booking exists
	 * 
	 * @param  int $schedule_id
	 */
	public static function checkBooking($schedule_id) 
	{
		$user = request()->user();
		return Consultation::where(['schedule_id' => $schedule_id, 'user_id' => $user->id])
						->whereIn('status', [Consultation::PENDING, Consultation::APPROVED])->exists();
	}

	/**
	 * Check if schedule is unavailable
	 * 
	 * @param  int $schedule_id
	 */
	public static function checkSchedule($schedule_id) 
	{
		$schedule = Schedule::find($schedule_id);
		$message = null;
		if($schedule->status == 0) {
			$message = "This schedule is unavailable right now";
		}
		return $message;
	}

	/**
	 * Check if consultation is refundable
	 * 
	 * @return boolean
	 */
	public function canRefund()
	{
		if($this->status == Consultation::APPROVED || $this->status == Consultation::COMPLETED) {
			return true;
		}
	}

	/**
	 * Check if chat is available
	 * 
	 * @return boolean
	 */
	public function canChat()
	{
		if(in_array($this->status, [Consultation::PENDING, Consultation::APPROVED])) {
			return true;
		}
	}

	/**
	 * Check if user has suffiecient credits
	 * 
	 * @param  int $doctorID
	 */
	public static function hasSufficientCredits($doctorID, $forDoctor, $userID)
	{
		if($forDoctor == 1) {
			$doctor = request()->user();
			$credits = User::find($userID)->countCredits();

			if($credits >= $doctor->consultation_fee) {
				return true;
			}
		} else {
			$user = request()->user();
			$credits = $user->countCredits();
			$doctor = Doctor::find($doctorID);

			if($credits >= $doctor->consultation_fee) {
				return true;
			}
		}

	}

	/**
	 * Check if consultation has review
	 * 
	 * @return boolean
	 */
	public function hasReview() 
	{
		if($this->review) {
			return true;
		}
		return false;
	}

	/**
	 * Check max chat request
	 * 
	 * @param  object $doctor
	 */
	public static function checkCurrentConsultation($doctor)
	{
		$now = Carbon::now()->subDays(1);

		$currConsultationCount = $doctor->consultations()->where(['type' => Consultation::CHAT, 'status' => Consultation::APPROVED])->count();
		if($currConsultationCount >= Consultation::MAX_CONSULTATION) {
			return false;
		}
		return true;
	}

}
