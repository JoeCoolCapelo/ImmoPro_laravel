<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Visite;

class VisiteStatutChange extends Notification
{
    use Queueable;

    protected $visite;
    protected $statut;

    public function __construct(Visite $visite, $statut)
    {
        $this->visite = $visite;
        $this->statut = $statut;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $statutText = $this->statut;
        
        return (new MailMessage)
                    ->subject('Mise à jour de votre rendez-vous de visite')
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line('Le statut de votre rendez-vous pour le bien "' . $this->visite->bien->titre . '" a été mis à jour : ' . strtoupper($statutText) . '.')
                    ->action('Voir la visite', route('visites.index'))
                    ->line('Merci d\'utiliser notre application !');
    }

    public function toArray($notifiable): array
    {
        return [
            'visite_id' => $this->visite->id,
            'bien_titre' => $this->visite->bien->titre,
            'statut' => $this->statut,
            'message' => 'Statut de visite ' . $this->statut . ' pour ' . $this->visite->bien->titre,
            'url' => route('visites.index'),
            'type' => 'visite_statut'
        ];
    }
}
