<?php

namespace App\Notifications;

use App\Models\Visite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConfirmationInteretClient extends Notification
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
        $bien = $this->visite->bien;

        return (new MailMessage)
            ->subject('✨ Intérêt enregistré : ' . $bien->titre)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous avons bien reçu votre confirmation d\'intérêt pour le bien « ' . $bien->titre . ' » suite à votre visite.')
            ->line('L\'agent immobilier ainsi que le propriétaire ont été immédiatement informés de votre décision.')
            ->line('Vous serez recontacté très prochainement pour discuter de la suite (offre d\'achat, contre-visite, etc.).')
            ->action('Revoir l\'annonce', route('biens.show', $bien))
            ->line('Merci d\'avoir choisi ImmoPro pour votre projet immobilier.');
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
            'bien_titre' => $this->visite->bien->titre,
            'message' => 'Confirmation d\'intérêt pour le bien « ' . $this->visite->bien->titre . ' » enregistrée.',
            'type' => 'confirmation_interet'
        ];
    }
}
