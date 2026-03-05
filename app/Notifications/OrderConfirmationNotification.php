<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderConfirmationNotification extends Notification
{
    use Queueable;

    public $order;
    public $pdfContent;
    public $pdfFileName;

    public function __construct(Order $order, $pdfContent = null, $pdfFileName = null)
    {
        $this->order = $order;
        $this->pdfContent = $pdfContent;
        $this->pdfFileName = $pdfFileName;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Order Confirmation #' . $this->order->order_number . ' - ' . env("APP_NAME"))
            ->view('emails.order-confirmation', [
                'order' => $this->order,
            ]);

        // Attach PDF if provided
        if ($this->pdfContent && $this->pdfFileName) {
            $mail->attachData($this->pdfContent, $this->pdfFileName, [
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}
