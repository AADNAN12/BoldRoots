<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactNotification extends Notification
{
    use Queueable;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Contact Message: ' . $this->data['subject'])
            ->view('emails.contact', [
                'name' => $this->data['name'],
                'email' => $this->data['email'],
                'phone' => $this->data['phone'] ?? 'N/A',
                'subject' => $this->data['subject'],
                'contact_message' => $this->data['message'],
            ]);
    }
}
