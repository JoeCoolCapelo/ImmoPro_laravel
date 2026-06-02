<?php

namespace App\Notifications;

use App\Models\Bien;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BienSoumis extends Notification
{
    use Queueable;

    protected $bien;

    public function __construct(Bien $bien)
    {
        $this->bien = $bien;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Nouveau bien en attente de validation')
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line('Un nouveau bien "' . $this->bien->titre . '" a été soumis par un propriétaire.')
                    ->action('Examiner le bien', route('biens.show', $this->bien))
                    ->line('Merci d\'utiliser notre application !');
    }

    public function toArray($notifiable): array
    {
        return [
            'bien_id' => $this->bien->id,
            'titre' => $this->bien->titre,
            'message' => 'Nouveau bien soumis : ' . $this->bien->titre,
            'url' => route('biens.show', $this->bien),
            'type' => 'bien_soumis'
        ];
    }
}
