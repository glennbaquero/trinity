<?php

namespace App\Notifications\Team;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ApproveCallPlan extends Notification
{
    use Queueable;

    public $med_rep;
    public $call;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($med_rep, $call)
    {
        $this->med_rep = $med_rep;
        $this->call = $call;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
                    ->greeting('Hello '.$this->med_rep->fullname.'!')
                    ->from('no-reply@trinity.com')
                    ->line('Your call plan has been approved')
                    ->line('Call information below: ')
                    ->line('Medical Reprensentative In-charge: '. $this->call->medicalRepresentative->fullname)
                    ->line('Doctor: '. $this->call->doctor->fullname)
                    ->line('Agenda: ' . $this->call->agenda)
                    ->line('Schedule At: ' . $this->call->scheduled_date)
                    ->subject(config('app.name') . ': Call Plan Approved');
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
            //
        ];
    }
}
