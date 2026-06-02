<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
        $agencyName = \App\Models\Setting::get('agency_name', 'ImmoPro');
        $logoPath = \App\Models\Setting::get('agency_logo');
        
        // Use agency logo if exists, otherwise a professional real estate icon from CDN
        $logoUrl = $logoPath 
            ? asset(\Illuminate\Support\Facades\Storage::url($logoPath)) 
            : 'https://cdn-icons-png.flaticon.com/512/609/609803.png'; // Professional House Icon

        return (new MailMessage)
                    ->subject('Bienvenue chez ' . $agencyName . ' !')
                    ->view('emails.welcome', [
                        'userName' => $notifiable->name,
                        'agencyName' => $agencyName,
                        'logo' => $logoUrl,
                        'dashboardUrl' => config('app.url') . '/dashboard',
                        'phone' => \App\Models\Setting::get('contact_phone', '+224 625 99 79 03'),
                        'email' => \App\Models\Setting::get('contact_email', 'josephbangoura0204@gmail.com'),
                        'address' => 'Conakry, Guinée'
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Bienvenue !',
            'message' => 'Votre compte a été créé avec succès. Bienvenue chez ' . \App\Models\Setting::get('agency_name', 'ImmoPro'),
            'url' => route('dashboard'),
        ];
    }
}
