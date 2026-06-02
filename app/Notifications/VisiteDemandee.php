<?php

namespace App\Notifications;

use App\Models\Visite;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VisiteDemandee extends Notification
{
    use Queueable;

    protected $visite;

    public function __construct(Visite $visite)
    {
        $this->visite = $visite;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Nouvelle demande de visite')
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line('Une nouvelle demande de visite a été effectuée pour le bien "' . $this->visite->bien->titre . '".')
                    ->line('Client : ' . $this->visite->client->name)
                    ->line('Date souhaitée : ' . $this->visite->date_visite->format('d/m/Y H:i'))
                    ->action('Gérer les visites', route('visites.index'))
                    ->line('Merci d\'utiliser notre application !');
    }

    public function toArray($notifiable): array
    {
        return [
            'visite_id' => $this->visite->id,
            'bien_titre' => $this->visite->bien->titre,
            'client_name' => $this->visite->client->name,
            'message' => 'Nouvelle demande de visite pour ' . $this->visite->bien->titre,
            'url' => route('visites.index'),
            'type' => 'visite_demandee'
        ];
    }
}
