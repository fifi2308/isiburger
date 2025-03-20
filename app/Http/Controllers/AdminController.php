<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail; // Mail pour la facture PDF
use PDF; // Pour générer la facture en PDF


class AdminController extends Controller
{
    /**
     * Applique les middlewares au contrôleur.
     */
   /* public function __construct()
    {
        // Applique le middleware auth pour s'assurer que l'utilisateur est authentifié
        $this->middleware('auth');  // Vérifie si l'utilisateur est authentifié
        
        // Applique le middleware admin pour s'assurer que l'utilisateur est administrateur
        $this->middleware('admin'); // Vérifie si l'utilisateur est un administrateur
    }*/
  


    /**
     * Affiche la page d'accueil du tableau de bord de l'administrateur.
     */
    public function index()
    {
        return view('dashboard.admin');
    }

    

     // Afficher la liste des commandes
     public function orders()
     {
         $orders = Order::paginate(10); // Affichage avec pagination
         return view('admin.orders', compact('orders'));
     }
 
     // Voir les détails d'une commande
     public function show($id)
     {
         $order = Order::findOrFail($id);
         return view('admin.show_order', compact('order'));
     }
 
     // Modifier le statut d'une commande
     public function updateStatus(Request $request, $id)
     {
         $order = Order::findOrFail($id);
         $order->status = $request->status;
 
         // Si le statut est "Prête", envoyer une facture en PDF
         if ($request->status == 'prete') {
             $pdf = PDF::loadView('admin.invoice', ['order' => $order]);
             Mail::to($order->customer_email)->send(new InvoiceMail($pdf));
         }
 
         // Si le statut est "Payée", enregistrer la date et le montant du paiement
         if ($request->status == 'payee') {
             $order->payment_date = now();
             $order->payment_amount = $order->price;
         }
 
         $order->save();
 
         return redirect()->route('admin.orders.index')->with('success', 'Statut de la commande mis à jour');
     }
 
     // Annuler une commande
     public function cancel($id)
     {
         $order = Order::findOrFail($id);
         $order->status = 'annulee'; // Vous pouvez définir le statut comme "Annulée"
         $order->save();
 
         return redirect()->route('admin.orders.index')->with('success', 'Commande annulée');
     }


    public function dashboard()
{
    return view('dashboard.admin');
}

}
