<?php

namespace App\Notifications\Care;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserSuccessfulReferral extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $product;
    protected $referral;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $referral)
    {
        $this->user = $user;
        $this->title = 'Successful Referral';
        $this->message = "Congratulation one of your referral (". $user->renderFullName() .") has a successful transaction";
        $this->referral = $referral;
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
                    ->subject(config('app.name') . ': ' . $this->title)
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
            'subject_id' => $this->referral->id, 
            'subject_type' => get_class($this->referral),
        ];
    }
}
