<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Burger;
use App\Models\Invoice;

class ClientController extends Controller
{
    public function index()
    {
        return view('dashboard.user');

    }

    public function orders()
{
    // Logique pour afficher les commandes du client
    return view('client.orders');
}
public function showOrders()
{
    // Récupérer tous les burgers ou les burgers filtrés selon certains critères
    $burgers = Burger::all(); // Ou utiliser une logique de filtrage comme 'Burger::where(...)->get()'

    // Passer la variable $burgers à la vue
    return view('client.orders', compact('burgers'));
}

public function showInvoices()
    {
        // Si vous avez un modèle Invoice, récupérez les factures du client
        $invoices = Invoice::where('client_id', auth()->user()->id)->get();

        // Retourner une vue avec les factures
        return view('client.invoices', compact('invoices'));
    }


    


}
