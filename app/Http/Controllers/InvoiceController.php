<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;


class InvoiceController extends Controller
{
   // Définir la méthode index
   public function index()
    {
        // Récupérer toutes les factures
        $invoices = Invoice::all();
        dd($invoices);
        // Retourner la vue avec les données
        return view('invoices.index', compact('invoices'));
    }
}
