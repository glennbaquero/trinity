<?php

namespace App\Notifications\Doctor;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProcessConsultation extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $consultation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($consultation, $consultation_number, $doctor_name, $status)
    {
        $this->consultation_number = $consultation_number;
        $this->doctor_name = $doctor_name;
        $this->status = $status;
        $this->title = 'Consultation was '.$this->status.'.';
        $this->message = "The Consultation No. ".$this->consultation_number." with Dr. " .$this->doctor_name. " has been ".$this->status.".";
        $this->consultation = $consultation;
    }  

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'title' => $this->title,
            'subject_id' => $this->status == 'approved' ? $this->consultation->id: $notifiable->id, 
            'subject_type' => $this->status == 'approved' ? get_class($this->consultation): get_class($notifiable) ,
        ];
    }
}
