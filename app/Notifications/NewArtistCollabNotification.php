<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewArtistCollabNotification extends Notification
{
    use Queueable;

    public $data; // On stocke les données du formulaire ici

    // Constructeur : on récupère les données
    public function __construct($data)
    {
        $this->data = $data;
    }

    // Via quels canaux envoyer ? (Ici 'mail', peut être aussi 'database')
    public function via($notifiable)
    {
        return ['mail'];
    }

    // Configuration de l'email
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle demande de Collab : ' . $this->data['first_name'])
            // On réutilise votre vue existante 'emails.artists-collab'
            ->view('emails.artists-collab', [
                'first_name' => $this->data['first_name'],
                'last_name' => $this->data['last_name'],
                'social_handle' => $this->data['social_handle'],
                'phone' => $this->data['phone'],
                'collab_message' => $this->data['message'],
            ]);
    }
}