<?php

namespace App\Notifications\Admin\RequestClaimReferrals;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RequestClaimReferralNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $subject;
    protected $reason;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($subject, $message, $reason = null)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->reason = $reason;
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
                    ->subject(env('APP_NAME') . ": ". $this->subject)
                    ->greeting('Hello '. $notifiable->renderName())  
                    ->from('no-reply@trinity.com')                                              
                    ->line($this->message)
                    ->line($this->reason ? 'Reason: '. $this->reason : '');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $message = $this->message;
        if($this->reason) {
            $message .= ' Reason: ' . $this->reason;
        }

        return [
            'message' => $this->message,
            'title' => $this->subject,
            'subject_id' => $notifiable->id, 
            'subject_type' => get_class($notifiable),
        ];
    }
}
