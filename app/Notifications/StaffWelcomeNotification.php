<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffWelcomeNotification extends Notification
{
    public $staffMember;
    public $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($staffMember, $password = null)
    {
        $this->staffMember = $staffMember;
        $this->password = $password;
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
        $appName = config('app.name');
        $appUrl = rtrim(config('app.url'), '/');

        $message = (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Welcome to ' . $appName . ' - Your Account Credentials')
            ->greeting('Welcome, ' . $this->staffMember->name . '!')
            ->line('Your account has been successfully created in ' . $appName . '.')
            ->line('Please use the following credentials to log in:')
            ->line('Email: ' . $this->staffMember->email);

        if ($this->password) {
            $message->line('Password: ' . $this->password);
        }

        if (!empty($appUrl)) {
            $message->action('Login Now', $appUrl);
        }

        return $message
            ->line('If you have any questions or need assistance, please contact your administrator.')
            ->line('Thank you!');
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
            'staff_member' => $this->staffMember,
            'message' => 'Welcome to ' . config('app.name'),
        ];
    }
}
