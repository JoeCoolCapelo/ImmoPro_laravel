<?php

namespace App\Notifications;

use App\Models\PaiementLoyer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaiementLoyerRecu extends Notification
{
    use Queueable;

    public function __construct(protected PaiementLoyer $paiement) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $bien = $this->paiement->bien;
        $mois = $this->paiement->date_echeance->translatedFormat('F Y');

        return (new MailMessage)
            ->subject('✅ Loyer reçu — ' . $mois . ' — ' . $bien->titre)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre paiement de loyer pour le mois de **' . $mois . '** concernant le bien « ' . $bien->titre . ' » a bien été enregistré.')
            ->line('**Montant :** ' . number_format($this->paiement->montant_loyer, 0, ',', ' ') . ' FCFA')
            ->line('**Date d\'enregistrement :** ' . now()->translatedFormat('d F Y'))
            ->action('Voir mes locations', route('transactions.index'))
            ->line('Merci pour votre ponctualité.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'paiement_id' => $this->paiement->id,
            'bien_titre'  => $this->paiement->bien->titre,
            'mois'        => $this->paiement->date_echeance->translatedFormat('F Y'),
            'montant'     => $this->paiement->montant_loyer,
            'message'     => 'Paiement de loyer reçu pour ' . $this->paiement->date_echeance->translatedFormat('F Y'),
            'type'        => 'paiement_loyer',
        ];
    }
}
