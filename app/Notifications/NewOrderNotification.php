<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewOrderNotification extends Notification
{
    public $order;

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
                    ->subject('Nouvelle commande reçue')
                    ->line('Une nouvelle commande a été passée.')
                    ->line('Numéro de commande: ' . $this->order->id)
                    ->action('Voir la commande', url('/orders/' . $this->order->id));
    }
}
