<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Payment;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
        ]);

        $payment = new Payment();
        $payment->user_id = $request->user_id;
        $payment->amount = $request->amount;
        $payment->payment_method = 'Cash';  // En espèces
        $payment->status = 'Validated';    // Statut du paiement
        $payment->save();

        return redirect()->route('payments.index');
    }

    // Afficher la liste des paiements
    public function index()
    {
        // Récupérer tous les paiements, incluant l'email de l'utilisateur
        $payments = Payment::with('user')->get();

        return view('payments.index', compact('payments'));
    }
}
