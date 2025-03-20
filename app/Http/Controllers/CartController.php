<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Burger;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Notifications\NewOrderNotification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;


class CartController extends Controller
{
  
    
    
    public function placeOrder(Request $request)
    {
        // Vérifier si le panier est vide
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Votre panier est vide.');
        }

        // Calculer le montant total de la commande
        $totalAmount = array_reduce($cart, function ($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);

        // Vérifier si l'email et le nom du client sont fournis
        $request->validate([
            'client_name'  => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
        ]);

        // Créer la commande
        $order = Order::create([
            'client_name'  => $request->client_name,
            'client_email' => $request->client_email,
            'total_amount' => $totalAmount,
            'status'       => 'en cours',
            'cart_details' => json_encode($cart),
        ]);

        $burger = Burger::find($request->id);
if ($burger) {
    session()->push('cart', [
        'burger_id' => $burger->id,  // Vérifie que l'ID du burger est bien transmis
        'quantity'  => $request->quantity,
        'price'     => $burger->price
    ]);
}



$orderData = $request->input('order_items'); // Exemple : ['burger_id' => 1, 'quantity' => 2]

    // Vérifier que le burger_id est bien présent dans chaque article de commande
    foreach ($orderData as $item) {
        if (!isset($item['burger_id']) || empty($item['burger_id'])) {
            return redirect()->back()->with('error', 'Le burger_id est manquant.');
        }
    }

    // Si tout est correct, on crée la commande
    foreach ($orderData as $item) {
        // Ici, vous créez chaque élément dans la table `order_items`
        OrderItem::create([
            'burger_id' => $item['burger_id'],
            'quantity' => $item['quantity'],
            'order_id' => $orderId, // L'ID de la commande actuelle
        ]);

    }



        // Ajouter les articles de la commande dans OrderItem
        foreach ($cart as $burgerId => $item) {
            // Vérifier si l'ID du burger est valide
            if (empty($burgerId)) {
                return back()->with('error', "L'ID du burger est manquant.");
            }


            $burger = Burger::find($burgerId);
            if (!$burger) {
                throw new \Exception("Burger non trouvé pour l'ID: " . $burgerId);
            }
            
            OrderItem::create([
                'order_id' => $order->id,
                'burger_id' => $burger->id,
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        // Vider le panier après la commande
        session()->forget('cart');

        // Envoyer un email de confirmation
        try {
            Mail::to($request->client_email)->send(new OrderConfirmation($order));
        } catch (\Exception $e) {
            return back()->with('error', "Erreur lors de l'envoi de l'email : " . $e->getMessage());
        }

        // Redirection avec message de succès
        return redirect()->route('cart.checkout')->with('success', 'Votre commande a été enregistrée avec succès!');
    }

    // Affiche le panier
   /* public function index()
    {
        $cart = session('cart', []);
        return view('cart.checkout', compact('cart'));
    }*/

    public function remove($id)
    {
        $cart = session()->get('cart', []);
    
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
    
        return redirect()->back()->with('success', 'Burger supprimé du panier.');
    }
    



   /* public function showCart()
{
    // Récupérer la commande ou en créer une nouvelle si elle n'est pas trouvée
    // ou une autre méthode pour récupérer la commande
    $order = Order::where('client_id', Auth::id())->latest()->first();

    return view('cart.view', compact('order'));  // Passer 'order' à la vue
}*/

public function showCart()
    {
        $cart = session('cart', []);
        return view('cart.checkout', compact('cart'));
    }

public function updateCartSession()
{
    // Récupérer le panier actuel de la session
    $cart = session()->get('cart', []);

    // Mettre à jour tous les éléments du panier en s'assurant que chaque élément ait une clé 'id'
    foreach ($cart as $key => $burger) {
        // Si l'élément n'a pas la clé 'id', on l'ajoute
        if (!isset($burger['id'])) {
            $cart[$key]['id'] = $key; // Utiliser la clé du tableau comme ID
        }
    }

    // Sauvegarder à nouveau le panier dans la session
    session()->put('cart', $cart);
}





    // Ajoute un burger au panier
    public function addToCart(Request $request, Burger $burger)
{
    // Mettre à jour la session du panier
    $this->updateCartSession();

    // Récupérer le panier actuel ou en créer un vide
    $cart = session()->get('cart', []);

    // Vérifier si le burger est déjà dans le panier
    if (isset($cart[$burger->id])) {
        // Si le burger est déjà dans le panier, on incrémente la quantité
        $cart[$burger->id]['quantity']++;
    } else {
        // Vérifier si l'image existe pour ce burger
        $image = $burger->image ? $burger->image : 'default.jpg'; // Si l'image est manquante, utiliser une image par défaut
        
        // Ajouter le burger avec son image et son id
        $cart[$burger->id] = [
            'id' => $burger->id,  // Ajouter explicitement l'ID
            'name' => $burger->name,
            'price' => $burger->price,
            'quantity' => 1,
            'image' => $image // Ajouter l'image du burger
        ];
    }

    // Sauvegarder le panier mis à jour dans la session
    session()->put('cart', $cart);
    //dd(session()->get('cart'));
   // dd(session()->get('cart')); 

    // Retourner à la page précédente avec un message de confirmation
    return redirect()->route('cart.view')->with('success', 'Burger ajouté au panier');
}

    

    // Supprime un burger du panier
    public function removeFromCart($burgerId)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$burgerId])) {
            unset($cart[$burgerId]);
            session()->put('cart', $cart);
        }
        
        return back()->with('success', 'Burger retiré du panier.');
    }

    // Vider complètement le panier
    public function clearCart()
    {
        session()->forget('cart');
        return back()->with('success', 'Panier vidé avec succès.');
    }

   
   
   
    // Affiche la page de checkout
    public function checkout()
{
    // Récupérer l'email du client depuis la session ou le modèle Auth (si connecté)
    $clientEmail = auth()->user()->email ?? session('client_email');  // Ou utilisez session si l'email est sauvegardé en session

    // Récupérer la dernière commande de cet utilisateur (ou toutes les commandes si nécessaire)
    $order = Order::where('client_email', $clientEmail)
                  ->where('status', 'en cours')  // Vous pouvez ajouter des filtres comme le statut si nécessaire
                  ->orderBy('created_at', 'desc')
                  ->first();  // Récupère la dernière commande

    if (!$order) {
        // Si aucune commande n'est trouvée, on redirige avec un message d'erreur
        return redirect()->route('cart.empty')->with('error', 'Aucune commande trouvée.');
    }

    // Récupérer les détails de la commande (les articles dans la commande)
    $orderItems = OrderItem::where('order_id', $order->id)->get();

    // Affichage de la vue du checkout avec les informations de la commande et des articles
    return view('cart.checkout', compact('order', 'orderItems'));
}


    // Valide et enregistre la commande
    public function validateOrder(Request $request)
    {
        dd($request->all()); 
        // Validation des données du formulaire
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'payment_method' => 'required|string',
        ]);


        // Récupérer l'email du client
    $clientEmail = auth()->user()->email ?? session('client_email');

    // Récupérer la dernière commande de cet utilisateur (si une commande est en cours)
    $order = Order::where('client_email', $clientEmail)
                  ->where('status', 'en cours')
                  ->orderBy('created_at', 'desc')
                  ->first();  // Récupère la dernière commande

    if (!$order) {
        return redirect()->route('cart.empty')->with('error', 'Aucune commande trouvée.');
    }


    $order->status = 'confirmée';
    $order->save();

    // Récupérer les articles de la commande
    $orderItems = OrderItem::where('order_id', $order->id)->get();

    // Envoyer l'email de confirmation
    Mail::to($order->client_email)->send(new OrderConfirmation($order, $orderItems));
   // Vider le panier après la confirmation de la commande
   session()->forget('cart');

   // Rediriger l'utilisateur vers une page de confirmation avec un message de succès
   return redirect()->route('cart.view')->with('success', 'Commande confirmée. Un email de confirmation vous a été envoyé.');
}
    
      /*  // Récupérer le panier de la session
        $cart = session('cart', []);
        
        // Vérifier si le panier est vide
        if (empty($cart)) {
            return redirect()->route('cart.empty')->with('error', 'Votre panier est vide.');
        }
    
        // Création de la commande
        $order = new Order();
        $order->client_name = $request->client_name;
        $order->client_email = $request->client_email;
        $order->total_price = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $order->save();
    
        // Ajout des articles à la commande
        foreach ($cart as $burgerId => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'burger_id' => $burgerId,
                'quantity' => $details['quantity'],
                'price' => $details['price']
            ]);
        }

        // Envoyer l'email de confirmation
    $orderItems = OrderItem::where('order_id', $order->id)->get();
    Mail::to($order->client_email)->send(new OrderConfirmation($order, $orderItems));

    
        // Notification à l'administrateur pour la nouvelle commande
        $admin = User::where('is_admin', true)->first();
        if ($admin) {
            try {
                $admin->notify(new NewOrderNotification($order));
            } catch (\Exception $e) {
                // Log des erreurs éventuelles
                Log::error('Erreur notification admin: ' . $e->getMessage());
            }
        }
    
        // Notification au client pour la confirmation de la commande
        try {
            $order->notify(new OrderConfirmationNotification($order));
        } catch (\Exception $e) {
            // Log des erreurs éventuelles
            Log::error('Erreur notification client: ' . $e->getMessage());
        }
    
        // Vider le panier après la commande
        session()->forget('cart');
    
        // Retourner un message de succès
        return redirect()->route('cart.view')->with('success', 'Commande passée avec succès.');
    }
    */
    
    public function empty()
{
    return view('cart.empty');  // Affiche une vue indiquant que le panier est vide
}



    public function add(Request $request)
{
    // Vérifier si le produit existe
    $burger = Burger::find($request->burger_id);
    if (!$burger) {
        return redirect()->back()->with('error', 'Produit introuvable.');
    }

    // Récupérer le panier actuel depuis la session
    $cart = session()->get('cart', []);

    // Ajouter ou mettre à jour le produit dans le panier
    if (isset($cart[$request->burger_id])) {
        $cart[$request->burger_id]['quantity'] += $request->quantity;
    } else {
        $cart[$request->burger_id] = [
            "name" => $burger->name,
            "price" => $burger->price,
            "quantity" => $request->quantity
        ];
    }

    // Sauvegarder le panier dans la session
    session()->put('cart', $cart);
    
    return redirect()->back()->with('success', 'Produit ajouté au panier.');
}


public function view()
{
    $cart = session()->get('cart', []);

    return view('cart.view', compact('cart'));
}




}
