<?php

namespace App\Notifications\Samples;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SampleItemDenied extends Notification implements ShouldQueue
{
    use Queueable;

    protected $item;
    protected $title;
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->item = $item;
        $this->title = "Sample Item #{$item->id} Denied";
        $this->message = "Sample Item #{$item->id} has been denied.";
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
                    ->greeting('Hello ' . $notifiable->renderName() . ',')
                    ->line($this->message)
                    ->line("Reason: {$this->item->reason}")
                    ->action('View Item', $this->item->renderShowUrl())
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
        return [
            'title' => $this->title,
            'message' => $this->message,
            'subject_id' => $this->item->id, 
            'subject_type' => get_class($this->item),
        ];
    }
}
