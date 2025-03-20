@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <!-- Affichage des articles dans le panier -->
        @if(session('cart') && count(session('cart')) > 0)
            <h2 class="mb-4">Votre Panier</h2>
            <link href="{{ asset('css/app.css') }}" rel="stylesheet">

            <!-- Affichage des burgers dans le panier -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Burger</th>
                        <th class="text-center">Prix</th>
                        <th class="text-center">Quantité</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp

                    <!-- Parcours des articles du panier -->
                    @foreach(session('cart') as $burger)
                        @php
                            $subtotal = $burger['price'] * $burger['quantity'];
                            $total += $subtotal;
                            // Définir une image par défaut si 'image' est manquante
                            $image = isset($burger['image']) ? $burger['image'] : 'default.jpg';
                        @endphp
                       <tr>
                        <td class="text-center">
                            <!-- Affichage de l'image du burger -->
                            @if(isset($burger['image']) && file_exists(public_path('images/' . $burger['image'])))
                                <img src="{{ asset('images/' . $burger['image']) }}" alt="{{ $burger['name'] }}" width="50">
                            @else
                            <img src="{{ asset('images/default.jpg') }}" alt="Image non disponible" width="50">
                            @endif
                          {{ $burger['name'] }}

                        </td>
                        <td class="text-center">{{ number_format($burger['price'], 2) }} €</td>
                        <td class="text-center">{{ $burger['quantity'] }}</td>
                        <td class="text-center">{{ number_format($subtotal, 2) }} €</td>
                        <td class="text-center">
                           

                            @if(isset($burger['id']))
    <form action="{{ route('cart.remove', $burger['id']) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce burger du panier ?');">
        @csrf
        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
    </form>
@else
    <span class="text-muted">ID manquant</span>
@endif

                        </td>
                        
                    </tr>
                    
                    @endforeach

                    <!-- Affichage du total du panier -->
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total :</strong></td>
                        <td class="text-center"><strong>{{ number_format($total, 2) }} €</strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <!-- Formulaire de paiement et de commande -->
            <form action="{{ route('cart.checkout') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="client_name" class="form-label">Nom du client :</label>
                    <input type="text" name="client_name" id="client_name" class="form-control" required>
                </div>
            
                <div class="mb-3">
                    <label for="client_email" class="form-label">Email du client :</label>
                    <input type="email" name="client_email" id="client_email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="payment_method" class="form-label">Mode de paiement :</label>
                    <select name="payment_method" id="payment_method" class="form-select" required>
                        <option value="cash">Espèces</option>
                        <!-- Ajoute d'autres options si nécessaire -->
                    </select>
                </div>
            
                <!-- Bouton Commander (envoie le formulaire) -->
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-success">Commander</button>
                </div>
            
                <!-- Affichage du message de succès après la commande -->
                @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                        @if(isset($order))
                            <a href="{{ route('emails.order_confirmation', ['id' => $order->id]) }}" class="btn btn-primary btn-sm">Voir la commande</a>
                        @else
                            <p class="text-danger">Erreur : la commande n'a pas été enregistrée correctement.</p>
                        @endif
                    </div>
                @endif
            </form>
            
            
        @else
            <div class="alert alert-warning text-center">Votre panier est vide.</div>
        @endif
    </div>
@endsection
