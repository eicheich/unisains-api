<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Ichtrojan\Otp\Otp;

class EmailVerificationNotification extends Notification
{
    use Queueable;
    public $message;
    public $mailer;
    public $fromEmail;
    public $subject;
    private $otp;


    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->message = 'Gunakan kode ini untuk verifikasi email anda: ';
        $this->subject = 'Verifikasi Email';
        $this->mailer = 'smtp';
        $this->fromEmail = config('mail.from.address');
        $this->otp = new Otp();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $otp = $this->otp->generate($notifiable->email,6,60);
        return (new MailMessage)
                    ->mailer('smtp')->subject($this->subject)->greeting('Halo, ' . $notifiable->first_name)
            ->line($this->message)->line('kode : '. $otp->token);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
