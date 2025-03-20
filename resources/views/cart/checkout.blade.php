@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Vérifier votre commande</h1>

        <h3>Commande #{{ $order->id }}</h3>
        <p><strong>Nom :</strong> {{ $order->client_name }}</p>
        <p><strong>Email :</strong> {{ $order->client_email }}</p>
        <p><strong>Méthode de paiement :</strong> {{ ucfirst($order->payment_method) }}</p>
        <p><strong>Total :</strong> €{{ number_format($totalPrice, 2) }}</p>

        <h4>Articles dans votre commande :</h4>
        <ul>
            @foreach ($orderItems as $item)
                <li>
                    {{ $item->burger->name }} - 
                    Quantité : {{ $item->quantity }} - 
                    Prix : €{{ number_format($item->price, 2) }}
                </li>
            @endforeach
        </ul>

        <form action="{{ route('cart.validate') }}" method="POST">
            @csrf

            <!-- Informations client -->
            <input type="hidden" name="client_name" value="{{ $order->client_name }}">
            <input type="hidden" name="client_email" value="{{ $order->client_email }}">
            <input type="hidden" name="payment_method" value="{{ $order->payment_method }}">
            <input type="hidden" name="total_price" value="{{ $totalPrice }}">

            <!-- Détails des articles -->
            @foreach ($orderItems as $item)
                <input type="hidden" name="burgers[]" value="{{ $item->burger->id }}">
                <input type="hidden" name="quantities[]" value="{{ $item->quantity }}">
                <input type="hidden" name="prices[]" value="{{ $item->price }}">
            @endforeach

            <button type="submit" class="btn btn-primary">Confirmer la commande</button>
        </form>
    </div>
@endsection
