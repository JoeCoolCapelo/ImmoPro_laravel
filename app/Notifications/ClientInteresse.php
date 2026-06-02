<?php

namespace App\Notifications;

use App\Models\Visite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientInteresse extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Visite $visite)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $client = $this->visite->client;
        $bien = $this->visite->bien;

        return (new MailMessage)
            ->subject('🔥 Client intéressé : ' . $bien->titre)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Bonne nouvelle ! Le client ' . $client->name . ' (' . $client->email . ') vient de confirmer son intérêt pour le bien « ' . $bien->titre . ' » suite à sa visite.')
            ->line('Informations du client :')
            ->line('- Nom : ' . $client->name)
            ->line('- Email : ' . $client->email)
            ->line('- Téléphone : ' . ($client->phone ?? 'Non renseigné'))
            ->action('Consulter la visite', route('visites.index'))
            ->line('Nous vous suggérons de prendre contact avec lui dans les plus brefs délais.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'visite_id' => $this->visite->id,
            'bien_id' => $this->visite->bien_id,
            'client_name' => $this->visite->client->name,
            'bien_titre' => $this->visite->bien->titre,
            'message' => 'Un client est intéressé par votre bien « ' . $this->visite->bien->titre . ' »',
            'type' => 'interet_client'
        ];
    }
}
