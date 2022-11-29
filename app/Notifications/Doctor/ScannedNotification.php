<?php

namespace App\Notifications\Doctor;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ScannedNotification extends Notification
{
    use Queueable;
    protected $user;
    protected $doctor;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $doctor)
    {
        $this->user = $user;
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
                    ->greeting('Hello '.$this->doctor->first_name.'!')
                    ->from('no-reply@trinity.com')
                    ->line($this->user->renderFullName().' is added you as a personal '. $this->doctor->specialization->name)
                    ->subject(config('app.name') . ': Added as a personal '. $this->doctor->specialization->name);
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
            'message' => $this->user->renderFullName().' is added you as a personal '. $this->doctor->specialization->name,
            'title' => 'Added as a personal '. $this->doctor->specialization->name,
            'subject_id' => $notifiable->id, 
            'subject_type' => get_class($notifiable),
        ];
    }
}
