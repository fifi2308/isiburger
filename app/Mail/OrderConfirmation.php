<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Construire le message
     */
    public function build()
    {
        return $this //->from('fatoufall0320@gmail.com', 'Admin') // Expéditeur
                    ->subject('Confirmation de commande') // Sujet de l'email
                    ->view('emails.order_confirmation')// Vue Blade pour l'email
                    ->with(['order' => $this->order]); // Passer la commande à la vue
    }

    /**
     * Récupérer l'enveloppe du message.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de commande', // Définir le sujet de l'email dans l'enveloppe
        );
    }

    /**
     * Récupérer la définition du contenu du message.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_confirmation', // Assurez-vous que cette vue existe dans le répertoire "resources/views/emails"
        );
    }

    /**
     * Récupérer les pièces jointes pour le message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
