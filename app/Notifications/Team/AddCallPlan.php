<?php

namespace App\Notifications\Team;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AddCallPlan extends Notification
{
    use Queueable;

    public $admin;
    public $call;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($admin, $call)
    {
        $this->admin = $admin;
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
                    ->greeting('Hello '.$this->admin->first_name.'!')
                    ->from('no-reply@trinity.com')
                    ->line('A new call plan has been added and needs your approval.')
                    ->line('Call information below: ')
                    ->line('Medical Reprensentative In-charge: '. $this->call->medicalRepresentative->fullname)
                    ->line('Doctor: '. $this->call->doctor->fullname)
                    ->line('Agenda: ' . $this->call->agenda)
                    ->line('Schedule At: ' . $this->call->scheduled_date)
                    ->action('View Call Plan', $this->call->renderShowUrl())
                    ->subject(config('app.name') . ': Call Plan Approval');
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
            'title' => 'Call plan approval',
            'message' => 'A new call plan has been added and needs your approval',
            'subject_id' => $this->call->id,
            'subject_type' => get_class($this->call),
        ];
    }
}
