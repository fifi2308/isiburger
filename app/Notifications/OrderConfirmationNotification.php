<?php

// app/Notifications/OrderConfirmationNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderConfirmationNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Confirmation de commande')
                    ->greeting('Bonjour ' . $this->order->client_name)
                    ->line('Votre commande a bien été reçue.')
                    ->line('Détails de la commande :')
                    ->line('Nom du burger : ' . $this->order->orderItems->pluck('burger.name')->implode(', '))
                    ->line('Total : ' . number_format($this->order->total_price, 2) . ' €')
                    ->action('Voir la commande', url('/order/' . $this->order->id))
                    ->line('Merci pour votre commande.');
    }
}
