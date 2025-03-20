<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Burger;

class CatalogController extends Controller
{
    // Affichage du catalogue des burgers
    public function index()
    {
        $burgers = Burger::paginate(5); // Assure-toi d'utiliser paginate() et non get()
        return view('catalog.index', compact('burgers'));
    }
    

    // Ajout d'un burger au panier (nécessite une connexion)
    public function addToCart($burgerId)
    {
        // Vérifie si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Veuillez vous inscrire ou vous connecter pour ajouter un burger au panier.');
        }

        // Récupération du burger
        $burger = Burger::findOrFail($burgerId);

        // Récupération du panier (depuis la session)
        $cart = session()->get('cart', []);

        // Ajout au panier
        if (isset($cart[$burgerId])) {
            $cart[$burgerId]['quantity']++;
        } else {
            $cart[$burgerId] = [
                'name' => $burger->name,
                'quantity' => 1,
                'price' => $burger->price,
                'image' => $burger->image,
            ];
        }

        // Sauvegarde du panier dans la session
        session()->put('cart', $cart);

        return redirect()->route('catalog.index')->with('message', 'Burger ajouté au panier !');
    }
}
