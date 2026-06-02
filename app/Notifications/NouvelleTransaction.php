<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NouvelleTransaction extends Notification
{
    use Queueable;

    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $typeLabel = $this->transaction->type === 'vente' ? 'Vente' : 'Location';
        
        return (new MailMessage)
                    ->subject('Confirmation de votre transaction')
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line('Une nouvelle transaction de type ' . $typeLabel . ' a été enregistrée pour le bien "' . $this->transaction->bien->titre . '".')
                    ->line('Montant : ' . number_format($this->transaction->montant, 0, ',', ' ') . ' GNF')
                    ->line('Date : ' . $this->transaction->date_transaction->format('d/m/Y'))
                    ->action('Voir les détails', route('transactions.show', $this->transaction))
                    ->line('Merci de votre confiance !');
    }

    public function toArray($notifiable): array
    {
        return [
            'transaction_id' => $this->transaction->id,
            'bien_titre' => $this->transaction->bien->titre,
            'montant' => $this->transaction->montant,
            'message' => 'Transaction confirmée pour ' . $this->transaction->bien->titre,
            'url' => route('transactions.show', $this->transaction),
            'type' => 'transaction_confirmee'
        ];
    }
}
