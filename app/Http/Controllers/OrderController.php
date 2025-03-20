<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Burger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderReadyMail; // Importer la classe pour l'envoi de mails
use App\Mail\OrderConfirmation;
use Barryvdh\DomPDF\Facade as PDF;

class OrderController extends Controller
{

    public function showCart()
{
    $order = Order::find(1); // Exemple pour récupérer une commande
    return view('cart.view', compact('order'));
}

    
    public function placeOrder(Request $request)
    {
        // Validation des données de la commande
        $validatedData = $request->validate([
            'user_id' => 'required',
            'total' => 'required|numeric',
            // Ajoute les autres champs nécessaires pour ta commande
        ]);

        // Création de la commande
        $order = Order::create($validatedData);

        // Envoi d'un email de confirmation au client
        Mail::to($request->user()->email)->send(new OrderConfirmation($order));

        // Notification à l'admin
        // Tu peux envoyer une notification à l'admin ou utiliser un autre mécanisme de notification
        $admin = User::where('role', 'admin')->first(); // Adapté à ta gestion d'utilisateur
        Mail::to($admin->email)->send(new NewOrderNotification($order));

        // Affichage du message de confirmation sur l'interface utilisateur
        return redirect()->route('order.success')->with('success', 'Votre commande a été passée avec succès !');
    }



    public function sendInvoice(Order $order)
    {
        $pdf = PDF::loadView('pdf.invoice', compact('order'));
        return $pdf->download('facture_' . $order->id . '.pdf');
    }
    

    // Liste des commandes du client
    public function index()
    {
        $orders = auth()->user()->orders; // Récupérer les commandes de l'utilisateur connecté
        return view('orders.index', compact('orders'));
    }
    
   /* public function sendInvoice($orderId)
{
    $order = Order::findOrFail($orderId);
    $order->status = 'ready';
    $order->save();

    Mail::to($order->client_email)->send(new InvoicePDF($order));

    return redirect()->back()->with('success', 'Facture envoyée au client.');
}*/

    // Créer une commande
    public function create()
    {
        $burgers = Burger::all(); // Liste des burgers disponibles
        return view('orders.create', compact('burgers'));
    }

    // Enregistrer une nouvelle commande
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'items' => 'required|array',
            'items.*.burger_id' => 'required|exists:burgers,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0.1',
        ]);
       
        $order = new Order();
        $order->customer_id = auth()->id(); // ID de l'utilisateur connecté
        $order->total_price = $request->total_price;
        $order->status = 'En attente'; // Statut par défaut
        $order->save();

        // Parcourir les articles de la commande
        foreach ($request->items as $item) {
            $burger = Burger::find($item['burger_id']);
            
            // Vérification de la disponibilité du stock
            if ($burger->stock < $item['quantity']) {
                return redirect()->route('orders.create')->with('error', 'Stock insuffisant pour certains produits.');
            }

            // Créer un item de commande
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->burger_id = $item['burger_id'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->price = $item['price']; // Enregistrer le prix de chaque article
            $orderItem->save();

            // Mettre à jour le stock du burger
            $burger->stock -= $item['quantity'];
            $burger->save();
        }

        return redirect()->route('orders.index')->with('success', 'Commande passée avec succès');
    }

    // Voir une commande
    public function show(Order $order)
    {
        // Vérifier que l'utilisateur connecté est bien celui qui a passé la commande
        if ($order->customer_id !== auth()->id()) {
            return abort(403); // Accès interdit
        }

        // Récupérer les articles de la commande
        $orderItems = $order->orderItems;

        return view('orders.show', compact('order', 'orderItems'));
    }

    // Liste des commandes pour les gestionnaires
    public function adminIndex()
    {
        $orders = Order::all(); // Récupérer toutes les commandes
        return view('admin.orders.index', compact('orders'));
    }

    // Modifier une commande
    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    // Mettre à jour une commande
    public function update(Request $request, Order $order)
    {
        // Validation des données de mise à jour
        $request->validate([
            'status' => 'required|string|in:en_attente,prête,livrée,annulée',
        ]);

        $order->status = $request->status;
        $order->save();

        // Si la commande est prête, envoyer la facture en PDF
        if ($order->status == 'prête') {
            // Générer la facture et l'envoyer par e-mail
            Mail::to($order->customer->email)->send(new OrderReadyMail($order));
        }

        return redirect()->route('admin.orders.index')->with('success', 'Commande mise à jour');
    }

    // Supprimer une commande
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Commande annulée');
    }

    public function storePayment(Request $request, Order $order)
    {
        // Validation des données du paiement
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:en_attente,réussi,échoué',
        ]);

        // Créer une nouvelle entrée de paiement
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->amount = $validated['amount'];
        $payment->status = $validated['status'];
        $payment->save();

        // Mettre à jour le statut de la commande si nécessaire
        if ($payment->status == 'réussi') {
            // Mettre à jour le statut de la commande à "payée"
            $order->status = 'payée';
            $order->payment_date = now();
            $order->save();
        }

        return redirect()->route('orders.index')->with('success', 'Paiement enregistré avec succès');
    }
}
