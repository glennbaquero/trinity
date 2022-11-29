<?php

namespace App\Notifications\Care;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UpdateInvoice extends Notification
{
    use Queueable;

    public $request;
    public $invoice;
    protected $title;
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Request $request, $invoice)
    {
        $this->request = $request;
        $this->invoice = $invoice;
        $this->title = 'Order Update';
        $this->message = 'Your order #'.$this->invoice->invoice_number.' has been updated to '.$this->invoice->status->name;
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
                    ->subject(config('app.name') . ': Order Update')
                    ->greeting('Hello '. $this->invoice->user->first_name. '!')
                    ->from('no-reply@trinity.com')
                    ->line($this->message)
                    ->line('Order Number: '.$this->invoice->invoice_number)
                    ->line('Date of Purchase: ' . $this->invoice->created_at->toDayDateTimeString())
                    ->line('Total Purchased: ' . 'Php ' . number_format($this->invoice->grand_total, 2, '.', ','))
                    ->line('Items Quantity: ' . $this->invoice->renderTotalItemsBought())                    
                    ->line('Thank you for using '. config('app.name') .'!');
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
