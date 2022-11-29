<?php

namespace App\Models\ConsultationChats;

use App\Extendables\BaseModel as Model;

use App\Services\PushService;
use App\Services\PushServiceDoctor;
use App\Traits\FileTrait;

use App\Models\Consultations\Consultation;

class ConsultationChat extends Model
{

	use FileTrait;

    /*
	|--------------------------------------------------------------------------
	| @Consts
	|--------------------------------------------------------------------------
	*/



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

	public function consultation()
	{
		return $this->belongsTo(Consultation::class)->withTrashed();
	}

	public function sender()
	{
		return $this->morphToOne();		
	}

	public function reciever()
	{
		return $this->morphToOne();		
	}

	/*
	|--------------------------------------------------------------------------
	| @Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Store resources to storage
	 * 
	 * @param  array $request
	 */
	public static function store($request, $cleanMessage = true)
	{
		$user = request()->user();
		$file = null;

		if($request->file) {
	        $file = static::storeImage($request, null, 'file', 'chat_images');
		}

		$message = self::create([
			'consultation_id' => $request['consultation_id'],
			'sender_id' => $user->id,
			'sender_type' => get_class($user),
			'receiver_id' => $request['receiver_id'],
			'receiver_type' => self::renderReceiverType(get_class($user)),
			'message' => $cleanMessage ? strip_tags($request['message']) : $request['message'],
			'file_path' => $file
		]);

		$message->sendPushMessage();
	}

	public function sendPushMessage() 
	{
		$title = "New Message";
		$message = $this->file_path ? 'Sent an attachment' : $this->message;
		$receiver = $this->receiver_type::find($this->receiver_id);

		if($this->receiver_type == 'App\Models\Users\User') {
			$service = new PushService($title, $message);
		} else {
			$service = new PushServiceDoctor($title, $message);
		}

		$service->pushToOne($receiver);
	}

	/**
	 * Filter resources from storage
	 * 
	 * @param  int $consultationID
	 * @return array
	 */
	public static function filter($consultationID, $isAdmin = false)
	{
		$user = request()->user();

   		$conversations = self::where([
   			'consultation_id' => $consultationID,
   		])->get();

   		$conversations = collect($conversations->map(function($convo) use ($isAdmin) {
    		return [
    			'id' => $convo->id,
    			'receiver' => self::checkIfReceiver($convo),
    			'receiver_name' => $convo->renderFullName($convo->receiver_type, $convo->receiver_id),
    			'receiver_image' => self::renderImage($convo->receiver_type, $convo->receiver_id),
    			'sender' => self::checkIfSender($convo),
    			'sender_name' => $convo->renderFullName($convo->sender_type, $convo->sender_id), 
    			'sender_image' => self::renderImage($convo->sender_type, $convo->sender_id),
    			'message' => $convo->message,
    			'file' => $convo->renderImagePath('file_path'),
    			'readable_date' => $convo->created_at->diffForHumans(),
    			'created_at' => $convo->created_at
    		];
		}));

   		/** Set read_at */
   		$chats = self::where(['receiver_id' => $user->id, 'read_at' => null])->update(['read_at' => now()]);		

        return $conversations;

	}


	/*
	|--------------------------------------------------------------------------
	| @Renders
	|--------------------------------------------------------------------------
	*/


	public function renderFullName($userType, $id) 
	{
		$user = $userType::find($id);
		if($user) {
			return $user->renderName();
		}
	}

	/**
	 * Render receiver type
	 * 
	 * @param  string $senderType
	 */
	public static function renderReceiverType($senderType)
	{
		if($senderType == 'App\Models\Users\User') {
			/** Make doctor the receiver */
			return 'App\Models\Users\Doctor';
		}
		return 'App\Models\Users\User';
	}

	public static function renderImage($type, $id)
	{
		$user = $type::find($id);

		if($user) {
			return $user->full_image;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/

	/**
	 * Check if the specified user is a receiver of the message
	 * 
	 * @param  object $convo
	 * @return boolean
	 */
	public static function checkIfReceiver($convo)
	{
		$user = request()->user();
        if($convo->receiver_id === $user->id && $convo->receiver_type == get_class($user)) {
        	return true;
        }

	}

	/**
	 * Check if the specified user is a sender of the message
	 * 
	 * @param  object $convo
	 * @return boolean
	 */
	public static function checkIfSender($convo)
	{
		$user = request()->user();
        if($convo->sender_id === $user->id && $convo->sender_type == get_class($user)) {
        	return true;
        }
	}

}
