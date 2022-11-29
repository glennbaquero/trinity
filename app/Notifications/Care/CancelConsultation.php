<?php

namespace App\Notifications\Care;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CancelConsultation extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $product;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($consultation_number, $doctor_name)
    {
        $this->consultation_number = $consultation_number;
        $this->doctor_name = $doctor_name;
        $this->title = 'Consultation was cancelled';
        $this->message = "The Consultation No. ".$this->consultation_number." with Dr. " .$this->doctor_name. " has been successfully cancelled.";
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(config('app.name') . ': Update Consultation Status')
                    ->greeting('Hello '.$notifiable->first_name.'!')
                    ->from('no-reply@trinity.com')
                    ->line($this->message);
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
            'subject_id' => $notifiable->id, 
            'subject_type' => get_class($notifiable),
        ];
    }
}
