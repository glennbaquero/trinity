<?php

namespace App\Notifications\Team;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use Carbon\Carbon;

class DoctorCallPlanNotif extends Notification
{
    use Queueable;

    protected $doctor;
    protected $call;

    protected $title;
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($doctor, $call)
    {
        $this->doctor = $doctor;
        $this->call = $call;

        $schedule = Carbon::parse($this->call->scheduled_date)->toFormattedDateString();

        $this->title = 'You have an appointment on '. $schedule;
        $this->message = 'You have an appointment with '.  $this->call->medicalRepresentative->fullname .' this coming ' . $schedule;
    
    // 
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
                    ->greeting('Hello '.$this->doctor->fullname)
                    ->from('no-reply@trinity.com')     
                    ->line($this->message)
                    ->line('Call information below: ')
                    ->line('Medical Reprensentative In-charge: '. $this->call->medicalRepresentative->fullname)
                    ->line('Doctor: '. $this->call->doctor->fullname)
                    ->line('Agenda: ' . $this->call->agenda)
                    ->line('Schedule At: ' . $this->call->scheduled_date)
                    ->subject(config('app.name') . ': '. $this->title);                    
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
            'title' => $this->title,
            'message' => $this->message,
            'subject_id' => $this->call->id,
            'subject_type' => get_class($this->call),
        ];
    }
}
