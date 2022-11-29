<?php
namespace App\Notifications\Web\Auth;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    protected $title;
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message = null)
    {
        $this->title = Lang::getFromJson('Verify Email Address');
        $this->message = $message ?? 'Email verification has been sent';
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        $docVerification = (new MailMessage)
                            ->subject(config('app.name') . ': ' . $this->title)
                            ->line($this->message)
                            ->line(Lang::getFromJson('Please click the button below to verify your email address.'))
                            ->action(
                                Lang::getFromJson('Verify Email Address'),
                                $this->verificationUrl($notifiable)
                            )
                            ->line(Lang::getFromJson('If you did not create an account, no further action is required.'));

        $userVerification = (new MailMessage)
                            ->subject(config('app.name') . ': ' . $this->title)
                            ->line($this->message)
                            ->line(Lang::getFromJson('Please click the button below to verify your email address.'))
                            ->action(
                                Lang::getFromJson('Verify Email Address'),
                                $this->verificationUrl($notifiable)
                            )
                            ->line(Lang::getFromJson('If you did not create an account, no further action is required.'));

        return $this->message ? $userVerification : $docVerification;
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $type = get_class($notifiable) == 'App\Models\Users\User' ? 'care': 'team';

        return URL::temporarySignedRoute(
            'web.verification.verify', Carbon::now()->addMinutes(1440), ['id' => $notifiable->getKey(), 'type' => $type]
        );
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */

    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
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
