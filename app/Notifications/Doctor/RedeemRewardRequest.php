<?php

namespace App\Notifications\Doctor;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RedeemRewardRequest extends Notification
{
    use Queueable;

    public $request;
    public $doctorr;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Request $request,$doctor)
    {
        $this->request = $request;
        $this->doctor = $doctor;
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
                    ->greeting('Hello '.$this->request->user()->first_name.'!')
                    ->from('no-reply@trinity.com')
                    ->line($this->doctor->fullname . ' has requested to redeem the reward ' . $this->request->name . ' and wants your approval')
                    ->subject(config('app.name') . ': Redeem Reward Approval');
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
