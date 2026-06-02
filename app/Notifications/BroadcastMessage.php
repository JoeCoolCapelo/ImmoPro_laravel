<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BroadcastMessage extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subject;
    protected $message;

    public function __construct($subject, $message)
    {
        $this->subject = $subject;
        $this->message = $message;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line($this->message)
            ->action('Accéder à la plateforme', url('/'))
            ->line('Merci de faire partie de notre communauté !');
    }

    public function toArray($notifiable): array
    {
        return [
            'subject' => $this->subject,
            'message' => $this->message,
            'url' => '/',
            'type' => 'broadcast'
        ];
    }
}
