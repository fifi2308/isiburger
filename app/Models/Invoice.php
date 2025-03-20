<?php
// app/Models/Invoice.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    // Table associée au modèle
    protected $table = 'invoices';

    // Colonnes que vous pouvez remplir massivement
    protected $fillable = [
        'order_id',       // ID de la commande associée
        'invoice_number', // Numéro de la facture
        'amount',         // Montant total
        'status',         // Statut de la facture (par exemple, 'paid', 'pending', 'canceled')
        'due_date',       // Date limite de paiement
        'payment_date',   // Date de paiement (le cas échéant)
    ];

    // Relation avec la commande (une facture appartient à une commande)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Vous pouvez ajouter d'autres relations ici selon vos besoins
    // Par exemple, si vous avez une relation avec un client :
    // public function customer() {
    //     return $this->belongsTo(Customer::class);
    // }

    // Définir les attributs mutateurs (si vous avez besoin de formatages spéciaux pour certaines colonnes)
    // Exemple : formatage de la date
    public function getFormattedDueDateAttribute()
    {
        return \Carbon\Carbon::parse($this->due_date)->format('d/m/Y');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
