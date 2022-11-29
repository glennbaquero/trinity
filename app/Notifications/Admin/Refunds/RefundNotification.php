<?php

namespace App\Notifications\Admin\Refunds;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RefundNotification extends Notification
{
    use Queueable;

    protected $subject;
    protected $message;
    protected $reason;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($subject, $type, $consultation, $reason = null)
    {
        $this->subject = $subject;
        $this->message = 'Your refund request for consultation no. ' . $consultation->consultation_number . ' has been '. $type . '.';
        if($reason) {
            $this->reason .= 'Reason: ' . $reason;
        }
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
                    ->line($this->message)
                    ->line($this->reason ? $this->reason: '')
                    ->line('Thank you for using our application!');
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
            $message .= "<br /> ". $this->reason;
        }

        return [
            'message' => $message,
            'title' => $this->subject,
            'subject_id' => $notifiable->id, 
            'subject_type' => get_class($notifiable),
        ];
    }
}
