<?php

namespace App\Notifications;

use App\Models\Bien;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BienStatutChange extends Notification
{
    use Queueable;

    protected $bien;
    protected $statut;

    public function __construct(Bien $bien, $statut)
    {
        $this->bien = $bien;
        $this->statut = $statut;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $statutText = $this->statut === 'publié' ? 'approuvé et publié' : 'rejeté';
        
        return (new MailMessage)
                    ->subject('Mise à jour du statut de votre bien')
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line('Votre bien "' . $this->bien->titre . '" a été ' . $statutText . '.')
                    ->action('Voir mon bien', route('biens.show', $this->bien))
                    ->line('Merci d\'utiliser notre application !');
    }

    public function toArray($notifiable): array
    {
        return [
            'bien_id' => $this->bien->id,
            'titre' => $this->bien->titre,
            'statut' => $this->statut,
            'message' => 'Le statut de votre bien "' . $this->bien->titre . '" est passé à : ' . $this->statut,
            'url' => route('biens.show', $this->bien),
            'type' => 'statut_change'
        ];
    }
}
